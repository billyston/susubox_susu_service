<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuDirectDepositCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\FlexySusuDirectDepositCreateRequest;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuDirectDepositCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexySusu
     * @param FlexySusuDirectDepositCreateRequest $flexySusuDirectDepositCreateRequest
     * @param FlexySusuDirectDepositCreateAction $flexySusuDirectDepositCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     * @throws MoneyMismatchException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        FlexySusuDirectDepositCreateRequest $flexySusuDirectDepositCreateRequest,
        FlexySusuDirectDepositCreateAction $flexySusuDirectDepositCreateAction
    ): JsonResponse {
        // Execute the FlexySusuDirectDepositCreateAction and return the JsonResponse
        return $flexySusuDirectDepositCreateAction->execute(
            customer: $customer,
            flexySusu: $flexySusu,
            request: $flexySusuDirectDepositCreateRequest->validated()
        );
    }
}
