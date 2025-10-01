<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\BizSusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use App\Http\Requests\V1\Susu\BizSusu\BizSusuApprovalRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Data\BizSusu\BizSusuResource;
use Domain\Susu\Enums\Account\AccountStatus;
use Domain\Susu\Models\BizSusu;
use Domain\Susu\Services\Account\AccountStatusUpdateService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuApprovalAction
{
    private AccountStatusUpdateService $accountStatusUpdateService;

    public function __construct(
        AccountStatusUpdateService $accountStatusUpdateService
    ) {
        $this->accountStatusUpdateService = $accountStatusUpdateService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        BizSusu $biz_susu,
        BizSusuApprovalRequest $bizSusuApprovalRequest,
    ): JsonResponse {
        // Execute the AccountStatusUpdateService and return the account resource
        $account = $this->accountStatusUpdateService->execute(
            account: $biz_susu->account,
            status: AccountStatus::APPROVED->value
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your biz susu account has been approved.',
            data: new BizSusuResource(
                resource: $account->biz
            ),
        );
    }
}
