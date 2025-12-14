<?php

declare(strict_types=1);

namespace App\Domain\Shared\Services;

use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

final class RecurringDebitStatusUpdateService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        Model $model,
        string $status,
    ): void {
        try {
            // Check if the model's table has the recurring_debit_status column
            if (self::hasRecurringDebitStatusField($model)) {
                $model->update([
                    'recurring_debit_status' => $status,
                ]);
            }
            // If the field does not exist -> do nothing
        } catch (
            Throwable $throwable
        ) {
            Log::error('Exception in RecurringDebitStatusUpdateService', [
                'model' => $model->toArray(),
                'mode' => $status,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            throw new SystemFailureException(
                message: 'There was an error while updating the recurring debit status.',
            );
        }
    }

    protected static function hasRecurringDebitStatusField(
        Model $model
    ): bool {
        return Schema::hasColumn(
            $model->getTable(),
            column: 'recurring_debit_status'
        );
    }
}
