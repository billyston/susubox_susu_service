<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\Pause;

use App\Application\Susu\Actions\IndividualSusu\BizSusu\Pause\BizSusuPauseCancelAction;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\Pause\BizSusuPauseCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuPauseCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param AccountPause $accountPause
     * @param BizSusuPauseCancelRequest $bizSusuPauseCancelRequest
     * @param BizSusuPauseCancelAction $bizSusuPauseCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        AccountPause $accountPause,
        BizSusuPauseCancelRequest $bizSusuPauseCancelRequest,
        BizSusuPauseCancelAction $bizSusuPauseCancelAction
    ): JsonResponse {
        // Execute the BizSusuPauseCancelAction and return the JsonResponse
        return $bizSusuPauseCancelAction->execute(
            accountPause: $accountPause,
        );
    }
}
