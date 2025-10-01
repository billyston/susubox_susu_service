<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\BizSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\BizSusu\BizSusuApprovalRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Actions\BizSusu\BizSusuApprovalAction;
use Domain\Susu\Models\BizSusu;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $biz_susu,
        BizSusuApprovalRequest $bizSusuApprovalRequest,
        BizSusuApprovalAction $bizSusuApprovalAction
    ): JsonResponse {
        // Execute the BizSusuApprovalAction and return the JsonResponse
        return $bizSusuApprovalAction->execute(
            customer: $customer,
            biz_susu: $biz_susu,
            bizSusuApprovalRequest: $bizSusuApprovalRequest
        );
    }
}
