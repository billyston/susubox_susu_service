<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuAccountLockApprovalAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\FlexySusuAccountLockApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuAccountLockApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param AccountLock $accountLock
     * @param FlexySusuAccountLockApprovalRequest $flexySusuAccountLockApprovalRequest
     * @param FlexySusuAccountLockApprovalAction $flexySusuAccountLockApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        AccountLock $accountLock,
        FlexySusuAccountLockApprovalRequest $flexySusuAccountLockApprovalRequest,
        FlexySusuAccountLockApprovalAction $flexySusuAccountLockApprovalAction
    ): JsonResponse {
        // Execute the FlexySusuAccountLockApprovalAction and return the JsonResponse
        return $flexySusuAccountLockApprovalAction->execute(
            customer: $customer,
            flexySusu: $flexySusu,
            accountLock: $accountLock,
        );
    }
}
