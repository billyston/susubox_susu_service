<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Customer;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Actions\CustomerCreateAction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CustomerCreateController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Request $request,
        CustomerCreateAction $customerCreateAction
    ): JsonResponse {
        // Execute the CustomerCreateAction and return the JsonResponse
        return $customerCreateAction->execute(
            request: $request
        );
    }
}
