<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Susu\DailySusu;

use App\Application\Susu\Actions\DailySusu\DailySusuCreateAction;
use App\Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\FrequencyNotFoundException;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Requests\V1\Susu\DailySusu\DailySusuCreateRequest;
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
