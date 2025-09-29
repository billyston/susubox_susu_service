<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\DailySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\DailySusu\DailySusuCancelRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Actions\DailySusu\DailySusuCancelAction;
use Domain\Susu\Exceptions\Account\CancellationNotAllowedException;
use Domain\Susu\Models\Account;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuCancelController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws CancellationNotAllowedException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        DailySusuCancelRequest $dailySusuCancelRequest,
        DailySusuCancelAction $dailySusuCancelAction
    ): JsonResponse {
        // Execute the DailySusuCancelAction and return the JsonResponse
        return $dailySusuCancelAction->execute(
            customer: $customer,
            account: $account,
            dailySusuCancelRequest: $dailySusuCancelRequest
        );
    }
}
