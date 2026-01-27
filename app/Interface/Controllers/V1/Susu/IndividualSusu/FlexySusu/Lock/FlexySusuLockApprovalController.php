<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\Lock;

use App\Application\Susu\Actions\IndividualSusu\FlexySusu\Lock\FlexySusuLockApprovalAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\Lock\FlexySusuLockApprovalRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuLockApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param AccountLock $accountLock
     * @param FlexySusuLockApprovalRequest $flexySusuAccountLockApprovalRequest
     * @param FlexySusuLockApprovalAction $flexySusuLockApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        AccountLock $accountLock,
        FlexySusuLockApprovalRequest $flexySusuAccountLockApprovalRequest,
        FlexySusuLockApprovalAction $flexySusuLockApprovalAction
    ): JsonResponse {
        // Execute the FlexySusuLockApprovalAction and return the JsonResponse
        return $flexySusuLockApprovalAction->execute(
            customer: $customer,
            flexySusu: $flexySusu,
            accountLock: $accountLock,
        );
    }
}
