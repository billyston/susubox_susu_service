<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\BizSusu\BizSusuApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        BizSusuApprovalRequest $bizSusuApprovalRequest,
        BizSusuApprovalAction $bizSusuApprovalAction
    ): JsonResponse {
        // Execute the BizSusuApprovalAction and return the JsonResponse
        return $bizSusuApprovalAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
        );
    }
}
