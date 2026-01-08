<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuAccountPauseCancelAction;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuAccountPauseCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuAccountPauseCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param AccountPause $accountPause
     * @param BizSusuAccountPauseCancelRequest $bizSusuAccountPauseCancelRequest
     * @param BizSusuAccountPauseCancelAction $bizSusuAccountPauseCancelAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        AccountPause $accountPause,
        BizSusuAccountPauseCancelRequest $bizSusuAccountPauseCancelRequest,
        BizSusuAccountPauseCancelAction $bizSusuAccountPauseCancelAction
    ): JsonResponse {
        // Execute the BizSusuAccountPauseCancelAction and return the JsonResponse
        return $bizSusuAccountPauseCancelAction->execute(
            accountPause: $accountPause,
        );
    }
}
