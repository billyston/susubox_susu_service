<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuAccountPauseCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuAccountPauseCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuAccountPauseCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuAccountPauseCreateRequest $dailySusuAccountPauseCreateRequest
     * @param DailySusuAccountPauseCreateAction $dailySusuAccountPauseCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuAccountPauseCreateRequest $dailySusuAccountPauseCreateRequest,
        DailySusuAccountPauseCreateAction $dailySusuAccountPauseCreateAction
    ): JsonResponse {
        // Execute the DailySusuAccountPauseCreateAction and return the JsonResponse
        return $dailySusuAccountPauseCreateAction->execute(
            dailySusu: $dailySusu,
            request: $dailySusuAccountPauseCreateRequest->validated()
        );
    }
}
