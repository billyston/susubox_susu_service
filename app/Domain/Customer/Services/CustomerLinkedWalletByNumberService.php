<?php

declare(strict_types=1);

namespace App\Domain\Customer\Services;

use App\Domain\Customer\Models\LinkedWallet;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CustomerLinkedWalletByNumberService
{
    public function execute(
        string $wallet_number
    ): ?LinkedWallet {
        try {
            // Normalize the wallet number
            $normalized = $this->normalizeWalletNumber(
                $wallet_number
            );

            return LinkedWallet::query()
                ->where('wallet_number', $normalized)
                ->orWhere('wallet_number', ltrim($normalized, '+'))
                ->orWhere('wallet_number', preg_replace('/^0/', '233', $normalized))
                ->first();
        } catch (
            ModelNotFoundException $exception
        ) {
            Log::warning('Linked wallet not found', [
                'wallet_number' => $wallet_number,
                'exception' => [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],
            ]);

            return null;
        } catch (
            Throwable $throwable
        ) {
            // Log system-level exceptions for audit and monitoring
            Log::error('System error in CustomerLinkedWalletByNumberService', [
                'wallet_number' => $wallet_number,
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
            ]);

            return null;
        }
    }

    private static function normalizeWalletNumber(
        string $number
    ): string {
        // Remove spaces, hyphens, and unwanted characters
        $number = preg_replace('/[^\d+]/', '', trim($number));

        // Use match to cleanly handle prefixes
        return match (true) {
            str_starts_with($number, '+233') => $number,
            str_starts_with($number, '233') => '+' . $number,
            str_starts_with($number, '0') => '+233' . substr($number, 1),

            default => $number,
        };
    }
}
