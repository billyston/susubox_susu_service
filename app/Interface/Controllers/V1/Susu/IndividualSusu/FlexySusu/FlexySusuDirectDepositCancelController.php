<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\FlexySusu;

use App\Application\Susu\Actions\FlexySusu\FlexySusuDirectDepositCancelAction;
use App\Domain\Account\Models\DirectDeposit;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu\FlexySusuDirectDepositCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FlexySusuDirectDepositCancelController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        FlexySusu $flexySusu,
        DirectDeposit $directDeposit,
        FlexySusuDirectDepositCancelRequest $flexySusuDirectDepositCancelRequest,
        FlexySusuDirectDepositCancelAction $flexySusuDirectDepositCancelAction
    ): JsonResponse {
        // Execute the FlexySusuDirectDepositCancelAction and return the JsonResponse
        return $flexySusuDirectDepositCancelAction->execute(
            customer: $customer,
            flexy_susu: $flexySusu,
            direct_deposit: $directDeposit,
            request: $flexySusuDirectDepositCancelRequest
        );
    }
}
