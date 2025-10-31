<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\BizSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\Jobs\BizSusu\BizSusuApprovalJob;
use App\Domain\Account\Enums\AccountStatus;
use App\Domain\Account\Services\AccountStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\BizSusu;
use App\Interface\Requests\V1\Susu\BizSusu\BizSusuApprovalRequest;
use App\Interface\Resources\V1\Susu\BizSusu\BizSusuResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuApprovalAction
{
    private AccountStatusUpdateService $accountStatusUpdateService;
    private BizSusuApprovalJob $bizSusuApprovalJob;

    public function __construct(
        AccountStatusUpdateService $accountStatusUpdateService,
        BizSusuApprovalJob $bizSusuApprovalJob
    ) {
        $this->accountStatusUpdateService = $accountStatusUpdateService;
        $this->bizSusuApprovalJob = $bizSusuApprovalJob;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        BizSusu $bizSusu,
        BizSusuApprovalRequest $bizSusuApprovalRequest,
    ): JsonResponse {
        // Execute the AccountStatusUpdateService and return the account resource
        $this->accountStatusUpdateService->execute(
            account: $bizSusu->account,
            status: AccountStatus::APPROVED->value
        );

        // Dispatch the BizSusuApprovalJob
        $this->bizSusuApprovalJob::dispatch(
            customer: $customer,
            bizSusu: $bizSusu,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your biz susu account has been approved.',
            data: new BizSusuResource(
                resource: $bizSusu->refresh()
            ),
        );
    }
}
