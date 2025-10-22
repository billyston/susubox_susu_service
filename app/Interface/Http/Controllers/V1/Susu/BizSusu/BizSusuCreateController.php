<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Susu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuCreateAction;
use App\Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\FrequencyNotFoundException;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Requests\V1\Susu\BizSusu\BizSusuCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuCreateController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws LinkedWalletNotFoundException
     * @throws FrequencyNotFoundException
     * @throws SusuSchemeNotFoundException
     */
    public function __invoke(
        Customer $customer,
        BizSusuCreateRequest $bizSusuCreateRequest,
        BizSusuCreateAction $bizSusuCreateAction
    ): JsonResponse {
        // Execute the BizSusuCreateAction and return the JsonResponse
        return $bizSusuCreateAction->execute(
            customer: $customer,
            bizSusuCreateRequest: $bizSusuCreateRequest
        );
    }
}
