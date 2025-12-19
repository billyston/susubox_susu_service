<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Customer;

use App\Application\Customer\Actions\CustomerWalletCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CustomerWalletCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param Request $request
     * @param CustomerWalletCreateAction $customerWalletCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        Request $request,
        CustomerWalletCreateAction $customerWalletCreateAction
    ): JsonResponse {
        // Execute the CustomerWalletCreateAction and return the JsonResponse
        return $customerWalletCreateAction->execute(
            customer: $customer,
            request: $request,
        );
    }
}
