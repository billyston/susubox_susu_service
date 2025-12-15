<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuDirectDepositApprovalAction;
use App\Domain\Account\Models\DirectDeposit;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuDirectDepositApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuDirectDepositApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DirectDeposit $directDeposit,
        DailySusuDirectDepositApprovalRequest $dailySusuDirectDepositApprovalRequest,
        DailySusuDirectDepositApprovalAction $dailySusuDirectDepositApprovalAction
    ): JsonResponse {
        // Execute the DailySusuDirectDepositApprovalAction and return the JsonResponse
        return $dailySusuDirectDepositApprovalAction->execute(
            customer: $customer,
            daily_susu: $dailySusu,
            direct_deposit: $directDeposit,
            request: $dailySusuDirectDepositApprovalRequest
        );
    }
}
