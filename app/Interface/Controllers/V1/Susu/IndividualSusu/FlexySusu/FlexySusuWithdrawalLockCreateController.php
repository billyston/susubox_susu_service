<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuWithdrawalLockCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\FlexySusuWithdrawalLockCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuWithdrawalLockCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param FlexySusuWithdrawalLockCreateRequest $flexySusuWithdrawalLockCreateRequest
     * @param FlexySusuWithdrawalLockCreateAction $flexySusuWithdrawalLockCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        FlexySusuWithdrawalLockCreateRequest $flexySusuWithdrawalLockCreateRequest,
        FlexySusuWithdrawalLockCreateAction $flexySusuWithdrawalLockCreateAction
    ): JsonResponse {
        // Execute the FlexySusuWithdrawalLockCreateAction and return the JsonResponse
        return $flexySusuWithdrawalLockCreateAction->execute(
            flexySusu: $flexySusu,
            request: $flexySusuWithdrawalLockCreateRequest->validated()
        );
    }
}
