<?php

declare(strict_types=1);

namespace Domain\Susu\Services\Account;

use App\Exceptions\Common\SystemFailureException;
use Domain\Susu\Enums\Account\AccountStatus;
use Domain\Susu\Models\Account;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

final class AccountStatusUpdateService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        Account $account,
        string $status,
    ): Account {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $account,
                    $status
                ) {
                    // Validate status
                    if (! in_array($status, AccountStatus::allowed(), true)) {
                        throw new InvalidArgumentException("Invalid account status: {$status}");
                    }

                    // Execute the update query
                    $account->update(['status' => $status]);

                    // Return the account resource
                    return $account->refresh();
                }
            );
        } catch (
            InvalidArgumentException $invalidArgumentException
        ) {
            throw $invalidArgumentException;
        } catch (
            QueryException $queryException
        ) {
            throw $queryException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountStatusUpdateService', [
                'account' => $account,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system failure occurred while updating the account status.',
            );
        }
    }
}
