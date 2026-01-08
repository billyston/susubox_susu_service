<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuAccountLockCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuAccountLockCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuAccountLockCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuAccountLockCreateRequest $dailySusuAccountLockCreateRequest
     * @param DailySusuAccountLockCreateAction $dailySusuAccountLockCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuAccountLockCreateRequest $dailySusuAccountLockCreateRequest,
        DailySusuAccountLockCreateAction $dailySusuAccountLockCreateAction
    ): JsonResponse {
        // Execute the DailySusuAccountLockCreateAction and return the JsonResponse
        return $dailySusuAccountLockCreateAction->execute(
            dailySusu: $dailySusu,
            request: $dailySusuAccountLockCreateRequest->validated()
        );
    }
}
