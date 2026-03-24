<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\FailedDebitRollover;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Interface\Resources\V1\PaymentInstruction\RecurringDepositResource;
use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuFailedDebitRolloverAction
{
    private SusuBoxServiceDispatcher $susuBoxServiceDispatcher;

    /**
     * @param SusuBoxServiceDispatcher $susuBoxServiceDispatcher
     */
    public function __construct(
        SusuBoxServiceDispatcher $susuBoxServiceDispatcher,
    ) {
        $this->susuBoxServiceDispatcher = $susuBoxServiceDispatcher;
    }

    /**
     * @param DailySusu $dailySusu
     * @return JsonResponse
     */
    public function execute(
        DailySusu $dailySusu,
    ): JsonResponse {
        // Extract the main resources
        $recurringDeposit = $dailySusu->account->recurringDeposit;

        // Dispatch to SusuBox Service (Payment Service)
        $this->susuBoxServiceDispatcher->send(
            service: config('susubox.payment.name'),
            endpoint: 'recurring-debits/'.$recurringDeposit->resource_id.'/rollover',
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'Your request is successful. You will be notified shortly.',
            data: new RecurringDepositResource(
                resource: $recurringDeposit->refresh()
            )
        );
    }
}
