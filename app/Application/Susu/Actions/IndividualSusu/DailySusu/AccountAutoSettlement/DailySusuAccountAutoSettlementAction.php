<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\AccountAutoSettlement;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Services\AccountAutoDebit\AccountAutoDebitService;
use App\Domain\Shared\Enums\Initiators;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuAccountAutoSettlementAction
{
    private AccountAutoDebitService $accountAutoDebitService;

    /**
     * @param AccountAutoDebitService $accountAutoDebitService
     */
    public function __construct(
        AccountAutoDebitService $accountAutoDebitService
    ) {
        $this->accountAutoDebitService = $accountAutoDebitService;
    }

    /**
     * @param DailySusu $dailySusu
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        DailySusu $dailySusu,
    ): JsonResponse {
        // Execute the AccountAutoDebitService
        $dailySusu = $this->accountAutoDebitService->execute(
            model: $dailySusu,
            initiator: Initiators::CUSTOMER->value,
            customer: $dailySusu->individual->customer,
        );

        // Notification dispatcher goes here

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: $dailySusu->auto_settlement === true ?
                'Your request is successful. Automated settlement is enabled.' :
                'Your request is successful. Automated settlement is disabled.',
        );
    }
}
