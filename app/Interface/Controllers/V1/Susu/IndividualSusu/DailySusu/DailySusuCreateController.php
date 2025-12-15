<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuCreateAction;
use App\Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\FrequencyNotFoundException;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuCreateRequest;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param DailySusuCreateRequest $dailySusuCreateRequest
     * @param DailySusuCreateAction $dailySusuCreateAction
     * @return JsonResponse
     * @throws FrequencyNotFoundException
     * @throws LinkedWalletNotFoundException
     * @throws SusuSchemeNotFoundException
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        DailySusuCreateRequest $dailySusuCreateRequest,
        DailySusuCreateAction $dailySusuCreateAction
    ): JsonResponse {
        // Execute the DailySusuCreateAction and return the DailySusu
        return $dailySusuCreateAction->execute(
            customer: $customer,
            request: $dailySusuCreateRequest->validated()
        );
    }
}
