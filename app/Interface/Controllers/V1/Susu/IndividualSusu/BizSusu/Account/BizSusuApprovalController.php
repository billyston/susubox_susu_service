<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\Account;

use App\Application\Susu\Actions\IndividualSusu\BizSusu\Account\BizSusuApprovalAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\Account\BizSusuApprovalRequest;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuApprovalController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param BizSusuApprovalRequest $bizSusuApprovalRequest
     * @param BizSusuApprovalAction $bizSusuApprovalAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        BizSusuApprovalRequest $bizSusuApprovalRequest,
        BizSusuApprovalAction $bizSusuApprovalAction
    ): JsonResponse {
        // Execute the BizSusuApprovalAction and return the JsonResponse
        return $bizSusuApprovalAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
        );
    }
}
