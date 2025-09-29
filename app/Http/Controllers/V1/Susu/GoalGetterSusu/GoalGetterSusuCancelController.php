<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\GoalGetterSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuCancelRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Actions\GoalGetterSusu\GoalGetterSusuCancelAction;
use Domain\Susu\Exceptions\Account\CancellationNotAllowedException;
use Domain\Susu\Models\Account;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuCancelController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws CancellationNotAllowedException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        GoalGetterSusuCancelRequest $goalGetterSusuCancelRequest,
        GoalGetterSusuCancelAction $goalGetterSusuCancelAction
    ): JsonResponse {
        // Execute the GoalGetterSusuCancelAction and return the JsonResponse
        return $goalGetterSusuCancelAction->execute(
            customer: $customer,
            account: $account,
            goalGetterSusuCancelRequest: $goalGetterSusuCancelRequest
        );
    }
}
