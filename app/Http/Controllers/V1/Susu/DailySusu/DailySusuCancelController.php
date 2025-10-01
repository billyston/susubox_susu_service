<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\DailySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\DailySusu\DailySusuCancelRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Actions\DailySusu\DailySusuCancelAction;
use Domain\Susu\Exceptions\Account\CancellationNotAllowedException;
use Domain\Susu\Models\DailySusu;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuCancelController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws CancellationNotAllowedException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $daily_susu,
        DailySusuCancelRequest $dailySusuCancelRequest,
        DailySusuCancelAction $dailySusuCancelAction
    ): JsonResponse {
        // Execute the DailySusuCancelAction and return the JsonResponse
        return $dailySusuCancelAction->execute(
            customer: $customer,
            daily_susu: $daily_susu,
            dailySusuCancelRequest: $dailySusuCancelRequest
        );
    }
}
