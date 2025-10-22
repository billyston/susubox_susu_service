<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Susu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuCreateAction;
use App\Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Requests\V1\Susu\FlexySusu\FlexySusuCreateRequest;
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
