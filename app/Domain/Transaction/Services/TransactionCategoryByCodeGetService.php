<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Services;

use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\TransactionCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Throwable;

final class TransactionCategoryByCodeGetService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        string $code,
    ): TransactionCategory {
        try {
            return TransactionCategory::where(
                'code',
                $code
            )->firstOrFail();
        } catch (
            ModelNotFoundException $modelNotFoundException
        ) {
            throw $modelNotFoundException;
        } catch (
            QueryException $queryException
        ) {
            throw $queryException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in TransactionCategoryByCodeGetService', [
                'code' => $code,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'An error occurred while trying to retrieve the transaction category.',
            );
        }
    }
}
