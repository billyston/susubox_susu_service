<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuAccountLockCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\FlexySusuAccountLockCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuAccountLockCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param FlexySusuAccountLockCreateRequest $flexySusuAccountLockCreateRequest
     * @param FlexySusuAccountLockCreateAction $flexySusuAccountLockCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        FlexySusuAccountLockCreateRequest $flexySusuAccountLockCreateRequest,
        FlexySusuAccountLockCreateAction $flexySusuAccountLockCreateAction
    ): JsonResponse {
        // Execute the FlexySusuAccountLockCreateAction and return the JsonResponse
        return $flexySusuAccountLockCreateAction->execute(
            flexySusu: $flexySusu,
            request: $flexySusuAccountLockCreateRequest->validated()
        );
    }
}
