<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu\Account;

use App\Application\Susu\Actions\IndividualSusu\FlexySusu\Account\FlexySusuApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\Account\FlexySusuApprovalRequest;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param FlexySusuApprovalRequest $flexySusuApprovalRequest
     * @param FlexySusuApprovalAction $flexySusuApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        FlexySusuApprovalRequest $flexySusuApprovalRequest,
        FlexySusuApprovalAction $flexySusuApprovalAction
    ): JsonResponse {
        // Execute the FlexySusuApprovalAction and return the JsonResponse
        return $flexySusuApprovalAction->execute(
            customer: $customer,
            flexySusu: $flexySusu,
        );
    }
}
