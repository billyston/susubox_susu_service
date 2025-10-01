<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\DailySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Actions\DailySusu\DailySusuShowAction;
use Domain\Susu\Models\DailySusu;
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
