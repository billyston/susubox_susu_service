<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Customer;

use App\Application\Customer\Actions\CustomerCreateAction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CustomerCreateController extends Controller
{
    /**
     * @param Request $request
     * @param CustomerCreateAction $customerCreateAction
     * @return JsonResponse
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
