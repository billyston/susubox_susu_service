<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement;

use App\Application\Account\Services\AccountBalanceGuardService;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\DTOs\IndividualSusu\DailySusu\Settlement\DailySusuSettlementRequestDTO;
use App\Application\Susu\Services\IndividualSusu\DailySusu\Settlement\DailySusuSettlementCalculationService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Cycle\DailySusuCycleSelectionService;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Settlement\DailySusuSettlementCreateService;
use App\Interface\Resources\V1\PaymentInstruction\SettlementResource;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class DailySusuSettlementCreateAction
{
    private DailySusuCycleSelectionService $dailySusuCycleSelectionService;
    private DailySusuSettlementCalculationService $dailySusuSettlementCalculationService;
    private AccountBalanceGuardService $balanceValidationService;
    private DailySusuSettlementCreateService $dailySusuSettlementService;

    /**
     * @param DailySusuCycleSelectionService $dailySusuCycleSelectionService
     * @param DailySusuSettlementCalculationService $dailySusuSettlementCalculationService
     * @param AccountBalanceGuardService $balanceValidationService
     * @param DailySusuSettlementCreateService $dailySusuAccountSettlementCreateService
     */
    public function __construct(
        DailySusuCycleSelectionService $dailySusuCycleSelectionService,
        DailySusuSettlementCalculationService $dailySusuSettlementCalculationService,
        AccountBalanceGuardService $balanceValidationService,
        DailySusuSettlementCreateService $dailySusuAccountSettlementCreateService,
    ) {
        $this->dailySusuCycleSelectionService = $dailySusuCycleSelectionService;
        $this->dailySusuSettlementCalculationService = $dailySusuSettlementCalculationService;
        $this->balanceValidationService = $balanceValidationService;
        $this->dailySusuSettlementService = $dailySusuAccountSettlementCreateService;
    }

    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param array $request
     * @return JsonResponse
     * @throws MoneyMismatchException
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     * @throws Throwable
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
        array $request
    ): JsonResponse {
        // Extract the main resources
        $account = $dailySusu->account;
        $accountBalance = $account->accountBalance;

        // Build the accountLockRequestDTO
        $requestDTO = DailySusuSettlementRequestDTO::fromPayload(
            payload: $request
        );

        // Execute the DailySusuCycleSelectionService
        $accountSettlementCycles = $this->dailySusuCycleSelectionService->execute(
            account: $account,
            scope:$requestDTO->scope,
            cycleResourceIDs: $requestDTO->cycleResourceIDs
        );

        // Execute the DailySusuCycleSelectionService
        $settlementCalculations = $this->dailySusuSettlementCalculationService->execute(
            accountCycles: $accountSettlementCycles,
            requestDTO: $requestDTO
        );

        // Execute the AccountBalanceGuardService
        $this->balanceValidationService->execute(
            availableBalance: $accountBalance->available_balance,
            debitAmount: $settlementCalculations->principal
        );

        // SettlementService should be executed here
        $settlement = $this->dailySusuSettlementService->execute(
            account: $account,
            accountCycles: $accountSettlementCycles,
            requestDTO: $requestDTO,
            requestVO: $settlementCalculations
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The account settlement was successfully created.',
            data: new SettlementResource(
                resource: $settlement
            )
        );
    }
}
