<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\GoalGetterSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\Jobs\GoalGetterSusu\GoalGetterSusuApprovalJob;
use App\Domain\Account\Enums\AccountStatus;
use App\Domain\Account\Services\AccountStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\GoalGetterSusu;
use App\Interface\Http\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuApprovalRequest;
use App\Interface\Http\Resources\V1\Susu\GoalGetterSusu\GoalGetterSusuResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuApprovalAction
{
    private AccountStatusUpdateService $accountStatusUpdateService;
    private GoalGetterSusuApprovalJob $goalGetterSusuApprovalJob;

    public function __construct(
        AccountStatusUpdateService $accountStatusUpdateService,
        GoalGetterSusuApprovalJob $goalGetterSusuApprovalJob
    ) {
        $this->accountStatusUpdateService = $accountStatusUpdateService;
        $this->goalGetterSusuApprovalJob = $goalGetterSusuApprovalJob;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
        GoalGetterSusuApprovalRequest $goalGetterSusuApprovalRequest,
    ): JsonResponse {
        // Execute the AccountStatusUpdateService and return the account resource
        $this->accountStatusUpdateService->execute(
            account: $goalGetterSusu->account,
            status: AccountStatus::APPROVED->value
        );

        // Dispatch the GoalGetterSusuApprovalJob
        $this->goalGetterSusuApprovalJob::dispatch(
            customer: $customer,
            goalGetterSusu: $goalGetterSusu,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your goal getter susu account has been approved.',
            data: new GoalGetterSusuResource(
                resource: $goalGetterSusu->refresh()
            ),
        );
    }
}
