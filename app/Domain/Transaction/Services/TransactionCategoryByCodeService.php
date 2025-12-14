<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Services;

use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\TransactionCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class TransactionCategoryByCodeService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
        string $category_code
    ): TransactionCategory {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $category_code,
            ) {
                return TransactionCategory::where([
                    ['code', '=', $category_code],
                ])->firstOrFail();
            });
        } catch (
            ModelNotFoundException $modelNotFoundException
        ) {
            throw new SystemFailureException(
                message: 'No active service found for the provided code.'
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in TransactionCategoryByCodeService', [
                'category' => $category_code,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to retrieve the transaction category.',
            );
        }
    }
}
