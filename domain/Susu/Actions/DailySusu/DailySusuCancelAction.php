<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\DailySusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use App\Http\Requests\V1\Susu\DailySusu\DailySusuCancelRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Exceptions\Account\CancellationNotAllowedException;
use Domain\Susu\Models\DailySusu;
use Domain\Susu\Services\Account\AccountCancelService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuCancelAction
{
    private AccountCancelService $accountCancelService;

    public function __construct(
        AccountCancelService $accountCancelService
    ) {
        $this->accountCancelService = $accountCancelService;
    }

    /**
     * @throws SystemFailureException
     * @throws CancellationNotAllowedException
     */
    public function execute(
        Customer $customer,
        DailySusu $daily_susu,
        DailySusuCancelRequest $dailySusuCancelRequest,
    ): JsonResponse {
        // Execute the AccountCancelService
        $this->accountCancelService->execute(
            account: $daily_susu->account,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The daily susu account setup has been cancelled.'
        );
    }
}
