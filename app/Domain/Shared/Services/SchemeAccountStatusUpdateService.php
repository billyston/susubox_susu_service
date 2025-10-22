<?php

declare(strict_types=1);

namespace App\Domain\Shared\Services;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Enums\RecurringDebitStatus;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class SchemeAccountStatusUpdateService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        Account $account,
    ): void {
        try {
            // Try to resolve any of the known susu models
            $susuModel = $account->daily ?? $account->biz ?? $account->goal ?? null;

            if ($susuModel) {
                $susuModel->updateRecurringDebitStatus(
                    status: RecurringDebitStatus::ACTIVE->value
                );
            }
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in SchemeAccountStatusUpdateService', [
                'account' => $account,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while updating the account recurring debit status.',
            );
        }
    }
}
