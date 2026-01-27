<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Pause;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Pause\DailySusuPauseCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\Pause\DailySusuPauseCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuPauseCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuPauseCreateRequest $dailySusuPauseCreateRequest
     * @param DailySusuPauseCreateAction $dailySusuPauseCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuPauseCreateRequest $dailySusuPauseCreateRequest,
        DailySusuPauseCreateAction $dailySusuPauseCreateAction
    ): JsonResponse {
        // Execute the DailySusuPauseCreateAction and return the JsonResponse
        return $dailySusuPauseCreateAction->execute(
            dailySusu: $dailySusu,
            request: $dailySusuPauseCreateRequest->validated()
        );
    }
}
