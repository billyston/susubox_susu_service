<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Susu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuShowAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\FlexySusu;
use App\Interface\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuShowController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexy_susu,
        FlexySusuShowAction $flexySusuShowAction
    ): JsonResponse {
        // Execute the FlexySusuShowAction and return the JsonResponse
        return $flexySusuShowAction->execute(
            customer: $customer,
            flexy_susu: $flexy_susu,
        );
    }
}
