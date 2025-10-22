<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Customer;

use App\Application\Customer\Actions\CustomerLinkedWalletCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CustomerLinkedWalletValidationController extends Controller
{
    /**
     * @throws SystemFailureException
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
