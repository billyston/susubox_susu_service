<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\FlexySusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use App\Http\Requests\V1\Susu\FlexySusu\FlexySusuApprovalRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Data\FlexySusu\FlexySusuResource;
use Domain\Susu\Enums\Account\AccountStatus;
use Domain\Susu\Models\FlexySusu;
use Domain\Susu\Services\Account\AccountStatusUpdateService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuApprovalAction
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
        FlexySusu $flexy_susu,
        FlexySusuApprovalRequest $flexySusuApprovalRequest,
    ): JsonResponse {
        // Execute the AccountStatusUpdateService and return the account resource
        $account = $this->accountStatusUpdateService->execute(
            account: $flexy_susu->account,
            status: AccountStatus::APPROVED->value
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your flexy susu account has been approved.',
            data: new FlexySusuResource(
                resource: $account->flexy
            ),
        );
    }
}
