<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\Account;

use App\Application\Susu\Actions\IndividualSusu\FlexySusu\Account\FlexySusuShowAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuShowController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param FlexySusuShowAction $flexySusuShowAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        FlexySusuShowAction $flexySusuShowAction
    ): JsonResponse {
        // Execute the FlexySusuShowAction and return the JsonResponse
        return $flexySusuShowAction->execute(
            customer: $customer,
            flexySusu: $flexySusu,
        );
    }
}
