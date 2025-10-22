<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Susu\GoalGetterSusu;

use App\Application\Susu\Actions\GoalGetterSusu\GoalGetterSusuCreateAction;
use App\Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\FrequencyNotFoundException;
use App\Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GoalGetterSusuCreateController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws LinkedWalletNotFoundException
     * @throws FrequencyNotFoundException
     * @throws SusuSchemeNotFoundException
     */
    public function __invoke(
        Customer $customer,
        GoalGetterSusuCreateRequest $goalGetterSusuCreateRequest,
        GoalGetterSusuCreateAction $goalGetterSusuCreateAction
    ): JsonResponse {
        // Execute the GoalGetterSusuCreateAction and return the JsonResponse
        return $goalGetterSusuCreateAction->execute(
            customer: $customer,
            goalGetterSusuCreateRequest: $goalGetterSusuCreateRequest
        );
    }
}
