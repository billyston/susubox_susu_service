<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\Jobs\DailySusu\DailySusuApprovalJob;
use App\Domain\Account\Enums\AccountStatus;
use App\Domain\Account\Services\AccountStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\DailySusu;
use App\Interface\Http\Requests\V1\Susu\DailySusu\DailySusuApprovalRequest;
use App\Interface\Http\Resources\V1\Susu\DailySusu\DailySusuResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuApprovalAction
{
    private AccountStatusUpdateService $accountStatusUpdateService;
    private DailySusuApprovalJob $dailySusuApprovalJob;

    public function __construct(
        AccountStatusUpdateService $accountStatusUpdateService,
        DailySusuApprovalJob $dailySusuApprovalJob
    ) {
        $this->accountStatusUpdateService = $accountStatusUpdateService;
        $this->dailySusuApprovalJob = $dailySusuApprovalJob;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
        DailySusuApprovalRequest $dailySusuApprovalRequest,
    ): JsonResponse {
        // Execute the AccountStatusUpdateService and return the account resource
        $this->accountStatusUpdateService->execute(
            account: $dailySusu->account,
            status: AccountStatus::APPROVED->value
        );

        // Dispatch the DailySusuApprovalJob
        $this->dailySusuApprovalJob::dispatch(
            customer: $customer,
            dailySusu: $dailySusu,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your daily susu account has been approved.',
            data: new DailySusuResource(
                resource: $dailySusu->refresh()
            ),
        );
    }
}
