<?php

declare(strict_types=1);

namespace App\Application\Account\Jobs\AccountPause;

use App\Application\Transaction\DTOs\RecurringDeposit\RecurringDepositResponseDTO;
use App\Domain\Account\Services\AccountPause\AccountPauseByResourceIdService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\SusuBox\Http\Requests\Payment\PaymentRequestHandler;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class AccountPauseResumeJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $resourceID
    ) {
        // ...
    }

    /**
     * @param AccountPauseByResourceIdService $accountPauseByResourceIdService
     * @param PaymentRequestHandler $dispatcher
     * @return void
     * @throws SystemFailureException
     */
    public function handle(
        AccountPauseByResourceIdService $accountPauseByResourceIdService,
        PaymentRequestHandler $dispatcher,
    ): void {
        // Execute the AccountPauseByResourceIdService and return the resource
        $accountPause = $accountPauseByResourceIdService->execute(
            accountPauseResource: $this->resourceID
        );

        // Build the RecurringDepositResponseDTO
        $responseDTO = RecurringDepositResponseDTO::fromDomain(
            paymentInstruction: $accountPause->payment,
            action: Statuses::RESUMED->value
        );

        // Dispatch to SusuBox Service (Payment Service)
        $dispatcher->sendToSusuBoxService(
            service: config('susubox.payment.name'),
            endpoint: 'recurring-debits/pause',
            data: $responseDTO->toArray(),
        );
    }
}
