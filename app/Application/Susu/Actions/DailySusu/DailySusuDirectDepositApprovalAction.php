<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu;

use App\Application\Account\Jobs\DirectDepositApprovedJob;
use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Services\DirectDepositStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Requests\V1\Susu\DailySusu\DailySusuDirectDepositApprovalRequest;
use App\Interface\Resources\V1\Account\DirectDepositResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuDirectDepositApprovalAction
{
    private DirectDepositStatusUpdateService $directDepositStatusUpdateService;

    public function __construct(
        DirectDepositStatusUpdateService $directDepositStatusUpdateService
    ) {
        $this->directDepositStatusUpdateService = $directDepositStatusUpdateService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        DailySusu $daily_susu,
        DailySusuDirectDepositApprovalRequest $request
    ): JsonResponse {
        // Execute the DirectDepositStatusUpdateService and return bool
        $this->directDepositStatusUpdateService->execute(
            direct_deposit: $direct_deposit,
            status: Statuses::APPROVED->value,
        );

        // Dispatch the DirectDepositApprovedJob
        DirectDepositApprovedJob::dispatch(
            directDeposit: $direct_deposit,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your request is successful. You will be notified shortly.',
            data: new DirectDepositResource(
                resource: $direct_deposit->refresh()
            )
        );
    }
}
