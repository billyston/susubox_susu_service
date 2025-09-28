<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\BizSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Actions\BizSusu\BizSusuGetAction;
use Domain\Susu\Models\Account;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuGetController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        BizSusuGetAction $bizSusuGetAction
    ): JsonResponse {
        // Execute the BizSusuGetAction and return the JsonResponse
        return $bizSusuGetAction->execute(
            customer: $customer,
            account: $account,
        );
    }
}
