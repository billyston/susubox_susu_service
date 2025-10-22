<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Susu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\FlexySusu;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Requests\V1\Susu\FlexySusu\FlexySusuApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuApprovalController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        FlexySusuApprovalRequest $flexySusuApprovalRequest,
        FlexySusuApprovalAction $flexySusuApprovalAction
    ): JsonResponse {
        // Execute the FlexySusuApprovalAction and return the JsonResponse
        return $flexySusuApprovalAction->execute(
            customer: $customer,
            flexySusu: $flexySusu,
            flexySusuApprovalRequest: $flexySusuApprovalRequest
        );
    }
}
