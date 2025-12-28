<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\FlexySusuCreateRequest;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusuCreateRequest $flexySusuCreateRequest
     * @param FlexySusuCreateAction $flexySusuCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        FlexySusuCreateRequest $flexySusuCreateRequest,
        FlexySusuCreateAction $flexySusuCreateAction
    ): JsonResponse {
        // Execute the FlexySusuCreateAction and return the FlexySusu resource
        return $flexySusuCreateAction->execute(
            customer: $customer,
            request: $flexySusuCreateRequest->validated()
        );
    }
}
