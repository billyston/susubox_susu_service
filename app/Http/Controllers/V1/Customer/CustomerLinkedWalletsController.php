<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Customer;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Actions\CustomerLinkedWalletsAction;
use Domain\Customer\Models\Customer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CustomerLinkedWalletsController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        Request $request,
        CustomerLinkedWalletsAction $customerLinkedWalletsAction
    ): JsonResponse {
        // Execute the CustomerLinkedWalletsAction and return the JsonResponse
        return $customerLinkedWalletsAction->execute(
            customer: $customer,
            request: $request,
        );
    }
}
