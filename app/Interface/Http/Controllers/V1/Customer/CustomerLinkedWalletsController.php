<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Customer;

use App\Application\Customer\Actions\CustomerLinkedWalletsAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Http\Controllers\Controller;
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
