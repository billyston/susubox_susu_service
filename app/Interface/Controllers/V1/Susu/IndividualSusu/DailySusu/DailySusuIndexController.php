<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuIndexAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuIndexController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusuIndexAction $dailySusuIndexAction
     * @return JsonResponse
     * @throws SusuSchemeNotFoundException
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
