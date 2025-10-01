<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\FlexySusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\FlexySusu\FlexySusuCancelRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Actions\FlexySusu\FlexySusuCancelAction;
use Domain\Susu\Exceptions\Account\CancellationNotAllowedException;
use Domain\Susu\Models\FlexySusu;
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
