<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuCreateAction;
use App\Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\FrequencyNotFoundException;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuCreateRequest;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusuCreateRequest $bizSusuCreateRequest
     * @param BizSusuCreateAction $bizSusuCreateAction
     * @return JsonResponse
     * @throws FrequencyNotFoundException
     * @throws LinkedWalletNotFoundException
     * @throws SusuSchemeNotFoundException
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        BizSusuCreateRequest $bizSusuCreateRequest,
        BizSusuCreateAction $bizSusuCreateAction
    ): JsonResponse {
        // Execute the BizSusuCreateAction and return the BizSusu
        return $bizSusuCreateAction->execute(
            customer: $customer,
            request: $bizSusuCreateRequest->validated()
        );
    }
}
