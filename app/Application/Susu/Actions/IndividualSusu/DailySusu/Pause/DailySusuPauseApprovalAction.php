<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Pause;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Application\Transaction\DTOs\RecurringDeposit\RecurringDepositResponseDTO;
use App\Domain\Account\Models\AccountPause;
use App\Domain\Account\Services\AccountPause\AccountPauseStatusUpdateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Resources\V1\Account\AccountPauseResource;
use App\Services\SusuBox\Http\Requests\Payment\PaymentRequestHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuPauseApprovalAction
{
    private AccountPauseStatusUpdateService $accountPauseStatusUpdateService;
    private PaymentRequestHandler $dispatcher;

    /**
     * @param AccountPauseStatusUpdateService $accountPauseStatusUpdateService
     * @param PaymentRequestHandler $dispatcher
     */
    public function __construct(
        AccountPauseStatusUpdateService $accountPauseStatusUpdateService,
        PaymentRequestHandler $dispatcher,
    ) {
        $this->accountPauseStatusUpdateService = $accountPauseStatusUpdateService;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param AccountPause $accountPause
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function execute(
        AccountPause $accountPause,
    ): JsonResponse {
        // Build the RecurringDepositResponseDTO
        $responseDTO = RecurringDepositResponseDTO::fromDomain(
            paymentInstruction: $accountPause->payment,
            action: Statuses::PAUSED->value
        );

        // Dispatch to SusuBox Service (Payment Service)
        $this->dispatcher->sendToSusuBoxService(
            service: config('susubox.payment.name'),
            endpoint: 'recurring-debits/pause',
            data: $responseDTO->toArray(),
        );

        // Execute the AccountPauseStatusUpdateService
        $this->accountPauseStatusUpdateService->execute(
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
