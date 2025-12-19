<?php

declare(strict_types=1);

namespace App\Application\Shared\Services;

use App\Domain\Shared\Models\RateLimiter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

final class RateLimiterService
{
    private string $key;

    /**
     * @param int $maxAttempts
     * @param int $decaySeconds
     * @param string $group
     * @param bool $ipBased
     * @param bool $userBased
     * @param bool $apiKeyBased
     */
    public function __construct(
        private readonly int $maxAttempts = 60,
        private readonly int $decaySeconds = 60,
        private readonly string $group = 'default',
        private readonly bool $ipBased = true,
        private readonly bool $userBased = false,
        private readonly bool $apiKeyBased = false
    ) {
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function forRequest(
        Request $request
    ): self {
        $identifiers = array_filter([
            $this->ipBased ? 'ip:' . $request->ip() : null,
            $this->userBased && $request->user() ? 'user:' . $request->user()->id : null,
            $this->apiKeyBased && $request->header('X-API-KEY')
                ? 'api_key:' . hash('sha256', $request->header('X-API-KEY'))
                : null,
        ]);

        if ($identifiers === []) {
            throw new \RuntimeException('No rate limit identifier available');
        }

        $this->key = implode('|', $identifiers);
        return $this;
    }

    /**
     * @return bool
     * @throws Throwable
     */
    public function attempt(
    ): bool {
        return DB::transaction(function (): bool {
            $now = now();
            $expiresAt = $now->copy()->addSeconds($this->decaySeconds);

            // Clean up old records first
            RateLimiter::where('expires_at', '<', $now)->delete();

            $rateLimit = RateLimiter::firstOrNew(
                ['key' => $this->key, 'group' => $this->group],
                ['attempts' => 0, 'reset_at' => $now->addSeconds($this->decaySeconds)]
            );

            if ($rateLimit->reset_at && $rateLimit->reset_at->isPast()) {
                $rateLimit->attempts = 0;
                $rateLimit->reset_at = $now->addSeconds($this->decaySeconds);
            }

            $rateLimit->attempts++;
            $rateLimit->expires_at = $expiresAt;
            $rateLimit->save();

            return $rateLimit->attempts <= $this->maxAttempts;
        });
    }

    /**
     * @return int
     */
    public function remainingAttempts(
    ): int {
        $rateLimit = RateLimiter::where('key', $this->key)
            ->where('group', $this->group)
            ->first();

        if (! $rateLimit || ($rateLimit->reset_at && $rateLimit->reset_at->isPast())) {
            return $this->maxAttempts;
        }

        return max(0, $this->maxAttempts - $rateLimit->attempts);
    }

    /**
     * @return Carbon|null
     */
    public function resetTime(
    ): ?Carbon {
        $rateLimit = RateLimiter::where('key', $this->key)
            ->where('group', $this->group)
            ->first();

        return $rateLimit?->reset_at;
    }

    /**
     * @return string[]
     */
    public function getHeaders(
    ): array {
        return [
            'X-RateLimit-Limit' => (string) $this->maxAttempts,
            'X-RateLimit-Remaining' => (string) $this->remainingAttempts(),
            'X-RateLimit-Reset' => (string) ($this->resetTime()?->timestamp ?? now()->addSeconds($this->decaySeconds)->timestamp),
            'Retry-After' => (string) ($this->resetTime()?->diffInSeconds(now()) ?? $this->decaySeconds),
        ];
    }
}
