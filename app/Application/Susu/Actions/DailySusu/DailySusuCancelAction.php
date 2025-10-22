<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Services\AccountCancelService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\DailySusu;
use App\Interface\Http\Requests\V1\Susu\DailySusu\DailySusuCancelRequest;
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
