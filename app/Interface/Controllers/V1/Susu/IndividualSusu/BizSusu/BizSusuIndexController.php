<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuIndexAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuIndexController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusuIndexAction $bizSusuIndexAction
     * @return JsonResponse
     * @throws SusuSchemeNotFoundException
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        BizSusuIndexAction $bizSusuIndexAction
    ): JsonResponse {
        // Execute the BizSusuIndexAction and return the JsonResponse
        return $bizSusuIndexAction->execute(
            customer: $customer,
        );
    }
}
