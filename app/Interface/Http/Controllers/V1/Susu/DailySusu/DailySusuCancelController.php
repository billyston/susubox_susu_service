<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Susu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\DailySusu;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Requests\V1\Susu\DailySusu\DailySusuCancelRequest;
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
