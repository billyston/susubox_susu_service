<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\PaymentInstruction;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCustomer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionType;
use App\Domain\Transaction\Models\TransactionCategory;
use Brick\Money\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class PaymentInstructionCreateService
{
    /**
     * @param Account $account
     * @param TransactionCategory $transactionCategory
     * @param AccountCustomer $accountCustomer
     * @param TransactionType $transactionType
     * @param Wallet $wallet
     * @param Money $amount
     * @param Money $charge
     * @param Money $total
     * @param bool $acceptedTerms
     * @param string $approvalStatus
     * @param array|null $metadata
     * @return PaymentInstruction
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
        TransactionCategory $transactionCategory,
        AccountCustomer $accountCustomer,
        TransactionType $transactionType,
        Wallet $wallet,
        Money $amount,
        Money $charge,
        Money $total,
        bool $acceptedTerms,
        string $approvalStatus = Statuses::PENDING->value,
        array $metadata = null,
    ): PaymentInstruction {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $account,
                $transactionCategory,
                $accountCustomer,
                $transactionType,
                $wallet,
                $amount,
                $charge,
                $total,
                $acceptedTerms,
                $approvalStatus,
                $metadata
            ) {
                // Create the PaymentInstruction and return the resource
                return $account->paymentInstructions()->create([
                    'transaction_category_id' => $transactionCategory->id,
                    'account_customer_id' => $accountCustomer->id,
                    'wallet_id' => $wallet->id,
                    'amount' => $amount,
                    'charge' => $charge,
                    'total' => $total,
                    'transaction_type' => $transactionType,
                    'accepted_terms' => $acceptedTerms,
                    'approval_status' => $approvalStatus,
                    'metadata' => $metadata,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in PaymentInstructionCreateService', [
                'account' => $account,
                'transaction_category' => $transactionCategory,
                'account_customer' => $accountCustomer,
                'transaction_type' => $transactionType,
                'wallet' => $wallet,
                'amount' => $amount,
                'charge' => $charge,
                'total' => $total,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                    'code' => $throwable->getCode(),
                    'trace' => $throwable->getTraceAsString(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to create the payment instruction.',
            );
        }
    }
}
