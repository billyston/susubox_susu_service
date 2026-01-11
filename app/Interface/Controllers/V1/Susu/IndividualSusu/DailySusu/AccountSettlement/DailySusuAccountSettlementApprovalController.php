<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\AccountSettlement;

use App\Application\Susu\Actions\DailySusu\AccountSettlement\DailySusuAccountSettlementApprovalAction;
use App\Domain\Account\Models\AccountSettlement;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\AccountSettlement\DailySusuAccountSettlementApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuAccountSettlementApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param AccountSettlement $accountSettlement
     * @param DailySusuAccountSettlementApprovalRequest $dailySusuAccountSettlementApprovalRequest
     * @param DailySusuAccountSettlementApprovalAction $dailySusuAccountSettlementApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        AccountSettlement $accountSettlement,
        DailySusuAccountSettlementApprovalRequest $dailySusuAccountSettlementApprovalRequest,
        DailySusuAccountSettlementApprovalAction $dailySusuAccountSettlementApprovalAction
    ): JsonResponse {
        // Execute the DailySusuAccountSettlementApprovalAction and return the JsonResponse
        return $dailySusuAccountSettlementApprovalAction->execute(
            dailySusu: $dailySusu,
            accountSettlement: $accountSettlement,
        );
    }
}
