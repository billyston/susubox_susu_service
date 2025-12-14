<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuShowAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuShowController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $daily_susu,
        DailySusuShowAction $dailySusuShowAction
    ): JsonResponse {
        // Execute the DailySusuShowAction and return the JsonResponse
        return $dailySusuShowAction->execute(
            customer: $customer,
            daily_susu: $daily_susu,
        );
    }
}
