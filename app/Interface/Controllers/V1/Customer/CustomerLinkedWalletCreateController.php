<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Customer;

use App\Application\Customer\Actions\CustomerLinkedWalletCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CustomerLinkedWalletCreateController extends Controller
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
