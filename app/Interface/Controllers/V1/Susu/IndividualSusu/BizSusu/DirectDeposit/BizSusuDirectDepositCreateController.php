<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\DirectDeposit;

use App\Application\Susu\Actions\IndividualSusu\BizSusu\DirectDeposit\BizSusuDirectDepositCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\DirectDeposit\BizSusuDirectDepositCreateRequest;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuDirectDepositCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param BizSusuDirectDepositCreateRequest $bizSusuDirectDepositCreateRequest
     * @param BizSusuDirectDepositCreateAction $bizSusuDirectDepositCreateAction
     * @return JsonResponse
     * @throws MoneyMismatchException
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        BizSusuDirectDepositCreateRequest $bizSusuDirectDepositCreateRequest,
        BizSusuDirectDepositCreateAction $bizSusuDirectDepositCreateAction
    ): JsonResponse {
        // Execute the BizSusuDirectDepositCreateAction and return the JsonResponse
        return $bizSusuDirectDepositCreateAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
            request: $bizSusuDirectDepositCreateRequest->validated()
        );
    }
}
