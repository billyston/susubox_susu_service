<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuDirectDepositApprovalAction;
use App\Domain\Account\Models\DirectDeposit;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuDirectDepositApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuDirectDepositApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        DirectDeposit $directDeposit,
        BizSusuDirectDepositApprovalRequest $bizSusuDirectDepositApprovalRequest,
        BizSusuDirectDepositApprovalAction $bizSusuDirectDepositApprovalAction
    ): JsonResponse {
        // Execute the BizSusuDirectDepositApprovalAction and return the JsonResponse
        return $bizSusuDirectDepositApprovalAction->execute(
            customer: $customer,
            biz_susu: $bizSusu,
            direct_deposit: $directDeposit,
            request: $bizSusuDirectDepositApprovalRequest
        );
    }
}
