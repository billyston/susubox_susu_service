<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuDirectDepositCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\FlexySusu\FlexySusuDirectDepositCreateRequest;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuDirectDepositCreateController extends Controller
{
    /**
     * @throws UnknownCurrencyException
     * @throws SystemFailureException
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
            flexy_susu: $flexySusu,
            request: $flexySusuDirectDepositCreateRequest
        );
    }
}
