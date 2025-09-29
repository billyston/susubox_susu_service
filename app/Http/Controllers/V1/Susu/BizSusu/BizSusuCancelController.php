<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\BizSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Susu\BizSusu\BizSusuCancelRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Actions\BizSusu\BizSusuCancelAction;
use Domain\Susu\Exceptions\Account\CancellationNotAllowedException;
use Domain\Susu\Models\Account;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuCancelController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws CancellationNotAllowedException
     */
    public function __invoke(
        Customer $customer,
        Account $account,
        BizSusuCancelRequest $bizSusuCancelRequest,
        BizSusuCancelAction $bizSusuCancelAction
    ): JsonResponse {
        // Execute the BizSusuCancelAction and return the JsonResponse
        return $bizSusuCancelAction->execute(
            customer: $customer,
            account: $account,
            bizSusuCancelRequest: $bizSusuCancelRequest
        );
    }
}
