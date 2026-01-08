<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuAccountLockCancelAction;
use App\Domain\Account\Models\AccountLock;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\FlexySusuAccountLockCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuAccountLockCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param AccountLock $accountLock
     * @param FlexySusuAccountLockCancelRequest $flexySusuAccountLockCancelRequest
     * @param FlexySusuAccountLockCancelAction $flexySusuAccountLockCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        AccountLock $accountLock,
        FlexySusuAccountLockCancelRequest $flexySusuAccountLockCancelRequest,
        FlexySusuAccountLockCancelAction $flexySusuAccountLockCancelAction
    ): JsonResponse {
        // Execute the FlexySusuAccountLockCancelAction and return the JsonResponse
        return $flexySusuAccountLockCancelAction->execute(
            accountLock: $accountLock,
        );
    }
}
