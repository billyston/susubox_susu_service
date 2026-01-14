<?php

declare(strict_types=1);

namespace App\Application\Susu\Jobs\IndividualSusu\DailySusu\AccountSettlement;

use App\Application\Account\ValueObjects\AccountAutoSettlementCalculationVO;
use App\Application\Transaction\DTOs\SettlementApprovalResponseDTO;
use App\Domain\Account\Services\AccountAutoSettlementCreateService;
use App\Domain\Account\Services\AccountCycleByResourceIdService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\SusuBox\Http\Requests\Payment\PaymentRequestHandler;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

final class DailySusuAutoSettlementJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param string $accountCycleResourceID
     */
    public function __construct(
        private readonly string $accountCycleResourceID
    ) {
        // ...
    }

    /**
     * @throws SystemFailureException
     * @throws Throwable
     */
    public function handle(
        AccountCycleByResourceIdService $accountCycleByResourceIdService,
        AccountAutoSettlementCreateService $accountAutoSettlementCreateService,
        PaymentRequestHandler $dispatcher,
    ): void {
        // Execute the AccountCycleByResourceIdService and return the resource
        $accountCycle = $accountCycleByResourceIdService->execute(
            accountCycleResource: $this->accountCycleResourceID,
        );

        // Extract the Account
        $account = $accountCycle->account;

        // Extract the DailySusu
        $dailySusu = $account->accountable->susu();

        // Guard the auto settlement
        if (
            $dailySusu->auto_settlement !== true ||
            $dailySusu->settlement_status !== Statuses::ACTIVE->value ||
            $account->status !== Statuses::ACTIVE->value
        ) {
            return;
        }

        // Build the AccountAutoSettlementCalculationVO
        $requestVO = AccountAutoSettlementCalculationVO::create(
            accountCycle: $accountCycle,
            charges: $dailySusu->susu_amount,
        );

        // Execute the AccountAutoSettlementCreateService and return the AccountSettlement resource
        $paymentInstruction = $accountAutoSettlementCreateService->execute(
            accountCycle: $accountCycle,
            account: $account,
            customer: $account->accountable->customer,
            wallet: $dailySusu->wallet,
            requestVO: $requestVO->toArray(),
        );

        // Build the response data
        $responseDTO = SettlementApprovalResponseDTO::fromDomain(
            paymentInstruction: $paymentInstruction,
            wallet: $paymentInstruction->wallet,
            product: $dailySusu,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $dispatcher->sendToSusuBoxService(
            service: config('susubox.payment.name'),
            endpoint: 'payouts',
            data: $responseDTO->toArray(),
        );
    }
}
