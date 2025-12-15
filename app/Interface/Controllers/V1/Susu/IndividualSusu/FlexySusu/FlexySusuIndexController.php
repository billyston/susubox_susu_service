<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuIndexAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Interface\Controllers\Shared\Controller;
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
