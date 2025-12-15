<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\DailySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Services\DirectDepositStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Requests\V1\Susu\IndividualSusu\DailySusu\DailySusuDirectDepositCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuDirectDepositCancelAction
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
        DailySusuDirectDepositCancelRequest $request
    ): JsonResponse {
        // Execute the DirectDepositStatusUpdateService and return the DirectDeposit resource
        $this->directDepositStatusUpdateService->execute(
            direct_deposit: $direct_deposit,
            status: Statuses::CANCELLED->value,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The direct deposit process was canceled successfully.',
        );
    }
}
