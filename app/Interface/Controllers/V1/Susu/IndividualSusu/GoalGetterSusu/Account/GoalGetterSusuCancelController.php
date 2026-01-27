<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\GoalGetterSusu\Account;

use App\Application\Susu\Actions\IndividualSusu\GoalGetterSusu\Account\GoalGetterSusuCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\Account\GoalGetterSusuCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @param GoalGetterSusuCancelRequest $goalGetterSusuCancelRequest
     * @param GoalGetterSusuCancelAction $goalGetterSusuCancelAction
     * @return JsonResponse
     * @throws CancellationNotAllowedException
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        GoalGetterSusuCancelRequest $goalGetterSusuCancelRequest,
        GoalGetterSusuCancelAction $goalGetterSusuCancelAction
    ): JsonResponse {
        // Execute the GoalGetterSusuCancelAction and return the JsonResponse
        return $goalGetterSusuCancelAction->execute(
            goalGetterSusu: $goalGetterSusu,
        );
    }
}
