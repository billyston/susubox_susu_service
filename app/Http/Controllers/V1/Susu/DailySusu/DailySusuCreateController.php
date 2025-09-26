<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\DailySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\DailySusu\DailySusuCreateRequest;
use Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\FrequencyNotFoundException;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Susu\Actions\DailySusu\DailySusuCreateAction;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DailySusuCreateController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws LinkedWalletNotFoundException
     * @throws SusuSchemeNotFoundException
     * @throws FrequencyNotFoundException
     */
    public function __invoke(
        Customer $customer,
        DailySusuCreateRequest $dailySusuCreateRequest,
        DailySusuCreateAction $dailySusuCreateAction
    ): JsonResponse {
        // Execute the DailySusuCreateAction and return the JsonResponse
        return $dailySusuCreateAction->execute(
            customer: $customer,
            dailySusuCreateRequest: $dailySusuCreateRequest
        );
    }
}
