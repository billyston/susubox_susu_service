<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuDirectDepositCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\DailySusu\DailySusuDirectDepositCreateRequest;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuDirectDepositCreateController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuDirectDepositCreateRequest $dailySusuDirectDepositCreateRequest,
        DailySusuDirectDepositCreateAction $dailySusuDirectDepositCreateAction
    ): JsonResponse {
        // Execute the DailySusuDirectDepositCreateAction and return the JsonResponse
        return $dailySusuDirectDepositCreateAction->execute(
            customer: $customer,
            daily_susu: $dailySusu,
            request: $dailySusuDirectDepositCreateRequest
        );
    }
}
