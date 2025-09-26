<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\DailySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Susu\Actions\DailySusu\DailySusuGetAction;
use Domain\Susu\Models\Account;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuGetController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        DailySusuGetAction $dailySusuGetAction
    ): JsonResponse {
        // Execute the DailySusuGetAction and return the JsonResponse
        return $dailySusuGetAction->execute(
            customer: $customer,
            account: $account,
        );
    }
}
