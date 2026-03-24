<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Services\Settlement;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCustomer;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionCreateService;
use App\Domain\Shared\Enums\Initiators;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Enums\TransactionType;
use App\Domain\Transaction\Services\TransactionCategoryByCodeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class SettlementAutoCreateService
{
    private TransactionCategoryByCodeService $transactionCategoryByCodeGetService;
    private PaymentInstructionCreateService $paymentInstructionCreateService;
    private SettlementCreateService $settlementCreateService;

    /**
     * @param TransactionCategoryByCodeService $transactionCategoryByCodeGetService
     * @param PaymentInstructionCreateService $paymentInstructionCreateService
     * @param SettlementCreateService $settlementCreateService
     */
    public function __construct(
        TransactionCategoryByCodeService $transactionCategoryByCodeGetService,
        PaymentInstructionCreateService $paymentInstructionCreateService,
        SettlementCreateService $settlementCreateService
    ) {
        $this->transactionCategoryByCodeGetService = $transactionCategoryByCodeGetService;
        $this->paymentInstructionCreateService = $paymentInstructionCreateService;
        $this->settlementCreateService = $settlementCreateService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        AccountCycle $accountCycle,
        Account $account,
        AccountCustomer $accountCustomer,
        Wallet $wallet,
        array $requestVO
    ): PaymentInstruction {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $accountCycle,
                    $account,
                    $accountCustomer,
                    $requestVO
                ) {
                    // Execute the TransactionCategoryByCodeService and return the resource
                    $transactionCategory = $this->transactionCategoryByCodeGetService->execute(
                        TransactionCategoryCode::SETTLEMENT_CODE->value
                    );

                    // Execute the PaymentInstructionCreateService and return the payment instruction resource
                    $paymentInstruction = $this->paymentInstructionCreateService->execute(
                        account: $account,
                        transactionCategory: $transactionCategory,
                        accountCustomer: $accountCustomer,
                        transactionType: TransactionType::DEBIT,
                        wallet: $accountCustomer->wallet,
                        amount: $requestVO['amount'],
                        charge: $requestVO['charge'],
                        total: $requestVO['total'],
                        acceptedTerms: $requestVO['accepted_terms'],
                        approvalStatus: Statuses::APPROVED->value,
                        metadata: $requestVO['metadata'] ?? null,
                    );

                    // Execute the SettlementCreateService and return the resource
                    $settlement = $this->settlementCreateService->execute(
                        account: $account,
                        paymentInstruction: $paymentInstruction,
                        initiator: Initiators::SCHEDULED,
                        settlementScope: $requestVO['metadata']['settlement_scope'],
                        principalAmount: $requestVO['amount'],
                        chargeAmount: $requestVO['charge'],
                        totalAmount: $requestVO['total'],
                    );

                    // Link the Settlement with the SettlementCycle
                    $settlement->settlementCycles()->create([
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
            Log::error('Exception in SettlementAutoCreateService', [
                'account_cycle' => $accountCycle,
                'account' => $account,
                'account_customer' => $accountCustomer,
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
