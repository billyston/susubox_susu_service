<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuDirectDepositCancelAction;
use App\Domain\Account\Models\DirectDeposit;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\DailySusu\DailySusuDirectDepositCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuDirectDepositCancelController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DirectDeposit $directDeposit,
        DailySusuDirectDepositCancelRequest $dailySusuDirectDepositCancelRequest,
        DailySusuDirectDepositCancelAction $dailySusuDirectDepositCancelAction
    ): JsonResponse {
        // Execute the DailySusuDirectDepositCancelAction and return the JsonResponse
        return $dailySusuDirectDepositCancelAction->execute(
            customer: $customer,
            daily_susu: $dailySusu,
            direct_deposit: $directDeposit,
            request: $dailySusuDirectDepositCancelRequest
        );
    }
}
