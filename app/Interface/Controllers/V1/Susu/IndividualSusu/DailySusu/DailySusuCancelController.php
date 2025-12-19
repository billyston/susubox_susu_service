<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuCancelRequest $dailySusuCancelRequest
     * @param DailySusuCancelAction $dailySusuCancelAction
     * @return JsonResponse
     * @throws CancellationNotAllowedException
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuCancelRequest $dailySusuCancelRequest,
        DailySusuCancelAction $dailySusuCancelAction
    ): JsonResponse {
        // Execute the DailySusuCancelAction
        return $dailySusuCancelAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
            request: $dailySusuCancelRequest->validated()
        );
    }
}
