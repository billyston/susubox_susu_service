<?php

declare(strict_types=1);

namespace App\Domain\Account\Services\AccountAutoDebit;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\PaymentInstruction\Models\SettlementCycle;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Models\TransactionCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountAutoSettlementCreateService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        AccountCycle $accountCycle,
        Account $account,
        Customer $customer,
        Wallet $wallet,
        array $requestVO
    ): PaymentInstruction {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $accountCycle,
                    $account,
                    $customer,
                    $wallet,
                    $requestVO
                ) {
                    // Get the transaction_category
                    $transactionCategory = TransactionCategory::where([['code', '=', TransactionCategoryCode::SETTLEMENT_CODE->value]])->firstOrFail();

                    // Create and return the PaymentInstruction resource
                    $paymentInstruction = PaymentInstruction::create([
                        'for_type' => $account->getMorphClass(),
                        'for_id' => $account->id,
                        'initiated_by_type' => Customer::class,
                        'initiated_by_id' => $customer->id,
                        'transaction_category_id' => $transactionCategory->id,
                        'account_id' => $account->id,
                        'wallet_id' => $wallet->id,
                        'amount' => $requestVO['amount'],
                        'charge' => $requestVO['charge'],
                        'total' => $requestVO['total'],
                        'transaction_type' => $requestVO['transaction_type'],
                        'accepted_terms' => $requestVO['accepted_terms'],
                        'approval_status' => Statuses::APPROVED->value,
                        'status' => Statuses::PENDING->value,
                        'extra_data' => $requestVO['extra_data'] ?? null,
                    ]);

                    // Create a new Settlement
                    $accountSettlement = Settlement::create([
                        'account_id' => $account->id,
                        'payment_instruction_id' => $paymentInstruction->id,
                        'settlement_scope' => $requestVO['extra_data']['settlement_scope'],
                        'principal_amount' => $requestVO['amount'],
                        'charge_amount' => $requestVO['charge'],
                        'total_amount' => $requestVO['total'],
                        'status' => $paymentInstruction->status,
                    ]);

                    // Link the Settlement with the SettlementCycle
                    SettlementCycle::create([
                        'account_settlement_id' => $accountSettlement->id,
                        'account_cycle_id' => $accountCycle->id,
                    ]);

                    // Return the PaymentInstruction resource
                    return $paymentInstruction->refresh();
                }
            );
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountAutoSettlementCreateService', [
                'account_cycle' => $accountCycle,
                'account' => $account,
                'customer' => $customer,
                'wallet' => $wallet,
                'request' => $requestVO,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while creating the account auto settlement.',
            );
        }
    }
}
