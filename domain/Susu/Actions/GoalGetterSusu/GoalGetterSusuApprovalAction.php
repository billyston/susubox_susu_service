<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\GoalGetterSusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use App\Http\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuApprovalRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Data\GoalGetterSusu\GoalGetterSusuResource;
use Domain\Susu\Enums\Account\AccountStatus;
use Domain\Susu\Models\Account;
use Domain\Susu\Services\Account\AccountStatusUpdateService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuApprovalAction
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
        Account $account,
        GoalGetterSusuApprovalRequest $goalGetterSusuApprovalRequest,
    ): JsonResponse {
        // Execute the AccountStatusUpdateService and return the account resource
        $account = $this->accountStatusUpdateService->execute(
            account: $account,
            status: AccountStatus::APPROVED->value
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your goal getter susu account has been approved.',
            data: new GoalGetterSusuResource(
                resource: $account->goal
            ),
        );
    }
}
