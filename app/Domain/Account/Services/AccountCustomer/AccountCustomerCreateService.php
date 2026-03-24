<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountCustomer;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCustomer;
use App\Domain\Customer\Enums\CustomerType;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountCustomerCreateService
{
    /**
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
        Customer $customer,
        Wallet $wallet,
        CustomerType $customerType,
    ): AccountCustomer {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $account,
                $customer,
                $wallet,
                $customerType,
            ) {
                // Linked wallet, and customer to the account
                return $account->accountCustomers()->create([
                    'customer_id' => $customer->id,
                    'wallet_id' => $wallet->id,
                    'customer_type' => $customerType,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountCustomerCreateService', [
                'account' => $account,
                'customer' => $customer,
                'wallet' => $wallet,
                'customer_type' => $customerType,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system failure occurred while trying to the account customer.',
            );
        }
    }
}
