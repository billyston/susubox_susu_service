<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\FailedDebitRollover;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\FailedDebitRollover\DailySusuFailedDebitRolloverAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\FailedDebitRollover\DailySusuFailedDebitRolloverRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuFailedDebitRolloverController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuFailedDebitRolloverRequest $dailySusuFailedDebitRolloverRequest
     * @param DailySusuFailedDebitRolloverAction $dailySusuFailedDebitRolloverAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuFailedDebitRolloverRequest $dailySusuFailedDebitRolloverRequest,
        DailySusuFailedDebitRolloverAction $dailySusuFailedDebitRolloverAction
    ): JsonResponse {
        // Execute the DailySusuFailedDebitRolloverAction and return the JsonResponse
        return $dailySusuFailedDebitRolloverAction->execute(
            dailySusu: $dailySusu,
        );
    }
}
