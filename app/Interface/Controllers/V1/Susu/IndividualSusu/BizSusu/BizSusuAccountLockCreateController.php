<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuAccountLockCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuAccountLockCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuAccountLockCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param BizSusuAccountLockCreateRequest $bizSusuWithdrawalLockCreateRequest
     * @param BizSusuAccountLockCreateAction $bizSusuWithdrawalLockCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        BizSusuAccountLockCreateRequest $bizSusuWithdrawalLockCreateRequest,
        BizSusuAccountLockCreateAction $bizSusuWithdrawalLockCreateAction
    ): JsonResponse {
        // Execute the BizSusuAccountLockCreateAction and return the JsonResponse
        return $bizSusuWithdrawalLockCreateAction->execute(
            bizSusu: $bizSusu,
            request: $bizSusuWithdrawalLockCreateRequest->validated()
        );
    }
}
