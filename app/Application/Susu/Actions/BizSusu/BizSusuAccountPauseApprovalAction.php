<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\BizSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Account\Services\AccountPause\AccountPauseStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Resources\V1\Account\AccountPauseResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuAccountPauseApprovalAction
{
    private AccountPauseStatusUpdateService $AccountPauseStatusUpdateService;

    public function __construct(
        AccountPauseStatusUpdateService $AccountPauseStatusUpdateService,
    ) {
        $this->AccountPauseStatusUpdateService = $AccountPauseStatusUpdateService;
    }

    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param AccountPause $accountPause
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        BizSusu $bizSusu,
        AccountPause $accountPause,
    ): JsonResponse {
        // Execute the AccountPauseStatusUpdateService
        $this->AccountPauseStatusUpdateService->execute(
            accountPause: $accountPause,
            status: Statuses::APPROVED->value
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your request is successful. You will be notified shortly.',
            data: new AccountPauseResource(
                resource: $accountPause->refresh()
            )
        );
    }
}
