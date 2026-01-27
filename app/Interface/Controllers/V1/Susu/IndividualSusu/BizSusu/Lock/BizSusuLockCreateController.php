<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\Lock;

use App\Application\Susu\Actions\IndividualSusu\BizSusu\Lock\BizSusuLockCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\Lock\BizSusuLockCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuLockCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param BizSusuLockCreateRequest $bizSusuWithdrawalLockCreateRequest
     * @param BizSusuLockCreateAction $bizSusuWithdrawalLockCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        BizSusuLockCreateRequest $bizSusuWithdrawalLockCreateRequest,
        BizSusuLockCreateAction $bizSusuWithdrawalLockCreateAction
    ): JsonResponse {
        // Execute the BizSusuLockCreateAction and return the JsonResponse
        return $bizSusuWithdrawalLockCreateAction->execute(
            bizSusu: $bizSusu,
            request: $bizSusuWithdrawalLockCreateRequest->validated()
        );
    }
}
