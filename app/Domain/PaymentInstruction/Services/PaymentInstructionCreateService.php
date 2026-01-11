<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Models\TransactionCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class PaymentInstructionCreateService
{
    /**
     * @param TransactionCategory $transactionCategory
     * @param Account $account
     * @param Wallet $wallet
     * @param Customer $customer
     * @param array $data
     * @return PaymentInstruction
     * @throws SystemFailureException
     */
    public function execute(
        TransactionCategory $transactionCategory,
        Account $account,
        Wallet $wallet,
        Customer $customer,
        array $data,
    ): PaymentInstruction {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $transactionCategory,
                $account,
                $wallet,
                $customer,
                $data,
            ) {
                // Create the PaymentInstruction and return the resource
                return PaymentInstruction::create([
                    'for_type' => $account->getMorphClass(),
                    'for_id' => $account->id,
                    'initiated_by_type' => Customer::class,
                    'initiated_by_id' => $customer->id,
                    'transaction_category_id' => $transactionCategory->id,
                    'account_id' => $account->id,
                    'wallet_id' => $wallet->id,
                    'amount' => $data['amount'],
                    'charge' => $data['charge'],
                    'total' => $data['total'],
                    'transaction_type' => $data['transaction_type'],
                    'accepted_terms' => $data['accepted_terms'],
                    'approval_status' => $data['approval_status'] ?? Statuses::PENDING->value,
                    'status' => Statuses::PENDING->value,
                    'extra_data' => $data['extra_data'] ?? null,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in PaymentInstructionCreateService', [
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to create the payment instruction.',
            );
        }
    }
}
