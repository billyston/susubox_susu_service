<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\BizSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\BizSusu\BizSusuCreateRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Actions\BizSusu\BizSusuCreateAction;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuCreateController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusuCreateRequest $bizSusuCreateRequest,
        BizSusuCreateAction $bizSusuCreateAction
    ): JsonResponse {
        // Execute the BizSusuCreateAction and return the JsonResponse
        return $bizSusuCreateAction->execute(
            customer: $customer,
            BizSusuCreateRequest: $bizSusuCreateRequest
        );
    }
}
