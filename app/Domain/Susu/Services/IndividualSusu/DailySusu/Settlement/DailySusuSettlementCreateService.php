<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Settlement;

use App\Application\Susu\DTOs\IndividualSusu\DailySusu\Settlement\DailySusuSettlementRequestDTO;
use App\Application\Susu\ValueObjects\IndividualSusu\DailySusu\Settlement\DailySusuSettlementCalculationVO;
use App\Domain\Account\Models\Account;
use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\PaymentInstruction\Services\PaymentInstruction\PaymentInstructionCreateService;
use App\Domain\PaymentInstruction\Services\Settlement\SettlementCreateService;
use App\Domain\Shared\Enums\Initiators;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Transaction\Enums\TransactionCategoryCode;
use App\Domain\Transaction\Enums\TransactionType;
use App\Domain\Transaction\Services\TransactionCategoryByCodeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuSettlementCreateService
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
     * @throws Throwable
     * @throws SystemFailureException
     */
    public function execute(
        Account $account,
        iterable $accountCycles,
        DailySusuSettlementRequestDTO $requestDTO,
        DailySusuSettlementCalculationVO $requestVO
    ): Settlement {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $account,
                $accountCycles,
                $requestDTO,
                $requestVO
            ) {
                // Extract the main resources
                $accountCustomer = $account->accountCustomer;

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
                    amount: $requestVO->principal,
                    charge: $requestVO->charges,
                    total: $requestVO->total,
                    acceptedTerms: $requestDTO->acceptedTerms,
                    metadata: $requestVO->toArray()['metadata']
                );

                // Execute the SettlementCreateService and return the resource
                $settlement = $this->settlementCreateService->execute(
                    account: $account,
                    paymentInstruction: $paymentInstruction,
                    initiator: Initiators::CUSTOMER,
                    settlementScope: $requestVO->toArray()['metadata']['settlement_scope'],
                    principalAmount: $requestVO->principal,
                    chargeAmount: $requestVO->charges,
                    totalAmount: $requestVO->total,
                );

                // Loop through the $accountCycles and link the settlement to account_cycle
                foreach ($accountCycles as $cycle) {
                    $settlement->settlementCycles()->create([
                        'account_cycle_id' => $cycle->id,
                    ]);
                }

                // Return the Settlement
                return $settlement;
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuSettlementService', [
                'account' => $account,
                'account_cycles' => $accountCycles,
                'request_dto' => $requestDTO,
                'request_vo' => $requestVO,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was system failure while creating daily susu settlements.',
            );
        }
    }
}
