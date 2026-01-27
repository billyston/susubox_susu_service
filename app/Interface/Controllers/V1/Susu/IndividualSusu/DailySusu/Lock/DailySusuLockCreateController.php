<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Lock;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Lock\DailySusuLockCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\Lock\DailySusuLockCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuLockCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuLockCreateRequest $dailySusuLockCreateRequest
     * @param DailySusuLockCreateAction $dailySusuLockCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuLockCreateRequest $dailySusuLockCreateRequest,
        DailySusuLockCreateAction $dailySusuLockCreateAction
    ): JsonResponse {
        // Execute the DailySusuLockCreateAction and return the JsonResponse
        return $dailySusuLockCreateAction->execute(
            dailySusu: $dailySusu,
            request: $dailySusuLockCreateRequest->validated()
        );
    }
}
