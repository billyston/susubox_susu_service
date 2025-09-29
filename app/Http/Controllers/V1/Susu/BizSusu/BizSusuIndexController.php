<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\BizSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Actions\BizSusu\BizSusuIndexAction;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuIndexController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     * @throws SusuSchemeNotFoundException
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
