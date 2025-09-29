<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\DailySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Actions\DailySusu\DailySusuIndexAction;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuIndexController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        DailySusuIndexAction $dailySusuIndexAction
    ): JsonResponse {
        // Execute the DailySusuIndexAction and return the JsonResponse
        return $dailySusuIndexAction->execute(
            customer: $customer,
        );
    }
}
