<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuWithdrawalLockCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuWithdrawalLockCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuWithdrawalLockCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param BizSusuWithdrawalLockCreateRequest $bizSusuWithdrawalLockCreateRequest
     * @param BizSusuWithdrawalLockCreateAction $bizSusuWithdrawalLockCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        BizSusuWithdrawalLockCreateRequest $bizSusuWithdrawalLockCreateRequest,
        BizSusuWithdrawalLockCreateAction $bizSusuWithdrawalLockCreateAction
    ): JsonResponse {
        // Execute the BizSusuWithdrawalLockCreateAction and return the JsonResponse
        return $bizSusuWithdrawalLockCreateAction->execute(
            bizSusu: $bizSusu,
            request: $bizSusuWithdrawalLockCreateRequest->validated()
        );
    }
}
