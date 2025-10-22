<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\FlexySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Susu\Jobs\FlexySusu\FlexySusuApprovalJob;
use App\Domain\Account\Enums\AccountStatus;
use App\Domain\Account\Services\AccountStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\FlexySusu;
use App\Interface\Http\Requests\V1\Susu\FlexySusu\FlexySusuApprovalRequest;
use App\Interface\Http\Resources\V1\Susu\FlexySusu\FlexySusuResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuApprovalAction
{
    private AccountStatusUpdateService $accountStatusUpdateService;
    private FlexySusuApprovalJob $flexySusuApprovalJob;

    public function __construct(
        AccountStatusUpdateService $accountStatusUpdateService,
        FlexySusuApprovalJob $flexySusuApprovalJob
    ) {
        $this->accountStatusUpdateService = $accountStatusUpdateService;
        $this->flexySusuApprovalJob = $flexySusuApprovalJob;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        FlexySusu $flexySusu,
        FlexySusuApprovalRequest $flexySusuApprovalRequest,
    ): JsonResponse {
        // Execute the AccountStatusUpdateService and return the account resource
        $this->accountStatusUpdateService->execute(
            account: $flexySusu->account,
            status: AccountStatus::APPROVED->value
        );

        // Dispatch the FlexySusuApprovalJob
        $this->flexySusuApprovalJob::dispatch(
            customer: $customer,
            flexySusu: $flexySusu,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your flexy susu account has been approved.',
            data: new FlexySusuResource(
                resource: $flexySusu->refresh()
            ),
        );
    }
}
