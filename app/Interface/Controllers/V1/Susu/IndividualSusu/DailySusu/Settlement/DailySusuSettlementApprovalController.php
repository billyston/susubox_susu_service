<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Settlement;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Settlement\DailySusuSettlementApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\PaymentInstruction\Models\Settlement;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\Settlement\DailySusuSettlementApprovalRequest;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuSettlementApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param Settlement $accountSettlement
     * @param DailySusuSettlementApprovalRequest $dailySusuSettlementApprovalRequest
     * @param DailySusuSettlementApprovalAction $dailySusuSettlementApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        Settlement $accountSettlement,
        DailySusuSettlementApprovalRequest $dailySusuSettlementApprovalRequest,
        DailySusuSettlementApprovalAction $dailySusuSettlementApprovalAction
    ): JsonResponse {
        // Execute the DailySusuSettlementApprovalAction and return the JsonResponse
        return $dailySusuSettlementApprovalAction->execute(
            dailySusu: $dailySusu,
            accountSettlement: $accountSettlement,
        );
    }
}
