<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu\Account;

use App\Application\Susu\Actions\IndividualSusu\DailySusu\Account\DailySusuApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\Account\DailySusuApprovalRequest;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @param DailySusuApprovalRequest $dailySusuApprovalRequest
     * @param DailySusuApprovalAction $dailySusuApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuApprovalRequest $dailySusuApprovalRequest,
        DailySusuApprovalAction $dailySusuApprovalAction
    ): JsonResponse {
        // Execute the DailySusuApprovalAction and return the JsonResponse
        return $dailySusuApprovalAction->execute(
            customer: $customer,
            dailySusu: $dailySusu,
        );
    }
}
