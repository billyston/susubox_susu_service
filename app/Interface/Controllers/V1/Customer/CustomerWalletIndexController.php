<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Customer;

use App\Application\Customer\Actions\CustomerWalletIndexAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CustomerWalletIndexController extends Controller
{
    /**
     * @param Customer $customer
     * @param Request $request
     * @param CustomerWalletIndexAction $customerWalletIndexAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        Request $request,
        CustomerWalletIndexAction $customerWalletIndexAction
    ): JsonResponse {
        // Execute the CustomerWalletIndexAction and return the JsonResponse
        return $customerWalletIndexAction->execute(
            customer: $customer,
            request: $request,
        );
    }
}
