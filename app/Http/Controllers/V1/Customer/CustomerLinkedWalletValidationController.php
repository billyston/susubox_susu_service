<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Customer;

use App\Exceptions\Common\SystemFailureExec;
use App\Http\Controllers\Controller;
use Domain\Customer\Actions\CustomerLinkedWalletCreateAction;
use Domain\Customer\Models\Customer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CustomerLinkedWalletValidationController extends Controller
{
    /**
     * @throws SystemFailureExec
     */
    public function __invoke(
        Customer $customer,
        Request $request,
        CustomerLinkedWalletCreateAction $customerLinkedWalletCreateAction
    ): JsonResponse {
        // Execute the CustomerLinkedWalletCreateAction and return the JsonResponse
        return $customerLinkedWalletCreateAction->execute(
            customer: $customer,
            request: $request,
        );
    }
}
