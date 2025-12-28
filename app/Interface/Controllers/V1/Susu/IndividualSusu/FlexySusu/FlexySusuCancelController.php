<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\FlexySusuCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param FlexySusu $flexy_susu
     * @param FlexySusuCancelRequest $flexySusuCancelRequest
     * @param FlexySusuCancelAction $flexySusuCancelAction
     * @return JsonResponse
     * @throws CancellationNotAllowedException
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexy_susu,
        FlexySusuCancelRequest $flexySusuCancelRequest,
        FlexySusuCancelAction $flexySusuCancelAction
    ): JsonResponse {
        // Execute the FlexySusuCancelAction and return the JsonResponse
        return $flexySusuCancelAction->execute(
            flexySusu: $flexy_susu,
        );
    }
}
