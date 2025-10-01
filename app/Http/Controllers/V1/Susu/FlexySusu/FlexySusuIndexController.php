<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\FlexySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Actions\FlexySusu\FlexySusuIndexAction;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuIndexController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     * @throws SusuSchemeNotFoundException
     */
    public function __invoke(
        Customer $customer,
        FlexySusuIndexAction $flexySusuIndexAction
    ): JsonResponse {
        // Execute the FlexySusuIndexAction and return the JsonResponse
        return $flexySusuIndexAction->execute(
            customer: $customer,
        );
    }
}
