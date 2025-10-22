<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Susu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\FlexySusu;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Requests\V1\Susu\FlexySusu\FlexySusuCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuCancelController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws CancellationNotAllowedException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexy_susu,
        FlexySusuCancelRequest $flexySusuCancelRequest,
        FlexySusuCancelAction $flexySusuCancelAction
    ): JsonResponse {
        // Execute the FlexySusuCancelAction and return the JsonResponse
        return $flexySusuCancelAction->execute(
            customer: $customer,
            flexy_susu: $flexy_susu,
            flexySusuCancelRequest: $flexySusuCancelRequest
        );
    }
}
