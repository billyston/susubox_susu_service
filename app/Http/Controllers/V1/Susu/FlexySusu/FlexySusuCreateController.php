<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\FlexySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\FlexySusu\FlexySusuCreateRequest;
use Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Susu\Actions\FlexySusu\FlexySusuCreateAction;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuCreateController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws LinkedWalletNotFoundException
     * @throws SusuSchemeNotFoundException
     */
    public function __invoke(
        Customer $customer,
        FlexySusuCreateRequest $flexySusuCreateRequest,
        FlexySusuCreateAction $flexySusuCreateAction
    ): JsonResponse {
        // Execute the FlexySusuCreateAction and return the JsonResponse
        return $flexySusuCreateAction->execute(
            customer: $customer,
            flexySusuCreateRequest: $flexySusuCreateRequest
        );
    }
}
