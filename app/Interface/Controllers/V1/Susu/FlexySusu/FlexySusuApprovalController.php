<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\FlexySusu\FlexySusuApprovalRequest;
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
        );
    }
}
