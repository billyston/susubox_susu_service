<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\FlexySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\FlexySusu\FlexySusuApprovalRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Actions\FlexySusu\FlexySusuApprovalAction;
use Domain\Susu\Models\Account;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        FlexySusuApprovalRequest $flexySusuApprovalRequest,
        FlexySusuApprovalAction $flexySusuApprovalAction
    ): JsonResponse {
        // Execute the FlexySusuApprovalAction and return the JsonResponse
        return $flexySusuApprovalAction->execute(
            customer: $customer,
            account: $account,
            flexySusuApprovalRequest: $flexySusuApprovalRequest
        );
    }
}
