<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuWithdrawalCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Transaction\Exceptions\InsufficientBalanceException;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuWithdrawalCreateRequest;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuWithdrawalCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param BizSusuWithdrawalCreateRequest $bizSusuWithdrawalCreateRequest
     * @param BizSusuWithdrawalCreateAction $bizSusuWithdrawalCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     * @throws MoneyMismatchException
     * @throws InsufficientBalanceException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        BizSusuWithdrawalCreateRequest $bizSusuWithdrawalCreateRequest,
        BizSusuWithdrawalCreateAction $bizSusuWithdrawalCreateAction
    ): JsonResponse {
        // Execute the BizSusuWithdrawalCreateAction and return the JsonResponse
        return $bizSusuWithdrawalCreateAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
            request: $bizSusuWithdrawalCreateRequest->validated()
        );
    }
}
