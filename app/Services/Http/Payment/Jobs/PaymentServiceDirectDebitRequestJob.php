<?php

declare(strict_types=1);

namespace App\Services\Http\Payment\Jobs;

use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\Http\Payment\PaymentService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

final class PaymentServiceDirectDebitRequestJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public PaymentService $service;

    public function __construct(
        public readonly array $data,
    ) {
        $this->service = new PaymentService;
    }

    /**
     * @throws Throwable
     */
    public function handle(
    ): void {
        try {
            Http::withHeaders([
                $this->service->headers,
            ])->post(
                url: $this->service->base_url.'susus/direct-debits',
                data: $this->data
            )->json();
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in PaymentServiceDirectDebitRequestJob', [
                'data' => $this->data,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureExec
            throw new SystemFailureException(
                message: 'An error occurred while processing your request.',
            );
        }
    }
}
