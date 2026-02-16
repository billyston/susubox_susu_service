<?php

declare(strict_types=1);

namespace App\Application\Account\Actions\Pause;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\RecurringDeposit\RecurringDepositRequestDTO;
use App\Application\Transaction\Jobs\RecurringDeposit\RecurringDepositPausedJob;
use App\Application\Transaction\Jobs\RecurringDeposit\RecurringDepositResumedJob;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Shared\Enums\Statuses;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class AccountPauseAction
{
    /**
     * @param array $request
     * @param AccountPause $accountPause
     * @return JsonResponse
     */
    public function execute(
        array $request,
        AccountPause $accountPause,
    ): JsonResponse {
        // Build the RecurringDepositRequestDTO
        $requestDTO = RecurringDepositRequestDTO::fromPayload(
            payload: $request,
        );

        // Check action and execute (RecurringDepositPausedJob, RecurringDepositResumedJob)
        match ($requestDTO->action) {
            Statuses::PAUSED->value => RecurringDepositPausedJob::dispatch(
                accountPauseResourceID: $accountPause->resource_id,
                requestDTO: $requestDTO,
            ),
            Statuses::RESUMED->value => RecurringDepositResumedJob::dispatch(
                accountPauseResourceID: $accountPause->resource_id,
                requestDTO: $requestDTO,
            ),

            default => null
        };

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_NO_CONTENT,
            message: 'Request accepted',
            description: 'The request was accepted for processing',
        );
    }
}
