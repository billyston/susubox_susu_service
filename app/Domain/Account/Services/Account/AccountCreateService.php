<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\Account;

use App\Domain\Account\Enums\AccountType;
use App\Domain\Account\Models\Account;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Models\SusuScheme;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountCreateService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
        SusuScheme $susuScheme,
        string $accountName,
        AccountType $accountType,
        bool $acceptedTerms
    ): Account {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $susuScheme,
                $accountName,
                $accountType,
                $acceptedTerms
            ) {
                // Create Financial Account
                return Account::create([
                    'susu_scheme_id' => $susuScheme->id,
                    'account_name' => $accountName,
                    'account_number' => Account::generateAccountNumber(),
                    'account_type' => $accountType,
                    'accepted_terms' => $acceptedTerms,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountCreateService', [
                'susu_scheme' => $susuScheme,
                'account_name' => $accountName,
                'account_type' => $accountType,
                'accepted_terms' => $acceptedTerms,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'trace' => $throwable->getTraceAsString(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system failure occurred while trying to create the account.',
            );
        }
    }
}
