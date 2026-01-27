<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\Lock;

use App\Application\Susu\Actions\IndividualSusu\FlexySusu\Lock\FlexySusuLockCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\Lock\FlexySusuLockCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuLockCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param FlexySusuLockCreateRequest $flexySusuLockCreateRequest
     * @param FlexySusuLockCreateAction $flexySusuLockCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        FlexySusuLockCreateRequest $flexySusuLockCreateRequest,
        FlexySusuLockCreateAction $flexySusuLockCreateAction
    ): JsonResponse {
        // Execute the FlexySusuLockCreateAction and return the JsonResponse
        return $flexySusuLockCreateAction->execute(
            flexySusu: $flexySusu,
            request: $flexySusuLockCreateRequest->validated()
        );
    }
}
