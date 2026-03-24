<?php

declare(strict_types=1);

namespace App\Application\Susu\Jobs\IndividualSusu\DailySusu\AutoSettlement;

use App\Application\PaymentInstruction\DTOs\Settlement\SettlementApprovalResponseDTO;
use App\Application\PaymentInstruction\ValueObject\Settlement\SettlementAutoCalculationVO;
use App\Domain\Account\Services\AccountCycle\AccountCycleByResourceIdService;
use App\Domain\PaymentInstruction\Services\Settlement\SettlementAutoCreateService;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;
use Brick\Money\Exception\MoneyMismatchException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
     * @param AccountCycleByResourceIdService $accountCycleByResourceIdService
     * @param SettlementAutoCreateService $settlementAutoCreateService
     * @param SusuBoxServiceDispatcher $susuBoxServiceDispatcher
     * @return void
     * @throws SystemFailureException
     * @throws MoneyMismatchException
     */
    public function handle(
        AccountCycleByResourceIdService $accountCycleByResourceIdService,
        SettlementAutoCreateService $settlementAutoCreateService,
        SusuBoxServiceDispatcher $susuBoxServiceDispatcher,
    ): void {
        // Execute the AccountCycleByResourceIdService and return the resource
        $accountCycle = $accountCycleByResourceIdService->execute(
            accountCycleResource: $this->accountCycleResourceID,
        );

        // Extract the key data (Account, DailySusu)
        $account = $accountCycle->account;
        $dailySusu = $account->dailySusu;

        $accountCustomer = $account->accountCustomer;
        $wallet = $accountCustomer->wallet;

        // Guard the auto settlement
        if (
            $dailySusu->auto_payout !== true ||
            $dailySusu->is_collateralized !== false ||
            $dailySusu->payout_status !== Statuses::ACTIVE->value ||
            $account->status !== Statuses::ACTIVE->value
        ) {
            return;
        }

        // Build the SettlementAutoCalculationVO
        $requestVO = SettlementAutoCalculationVO::create(
            accountCycle: $accountCycle,
        );

        // Execute the SettlementAutoCreateService and return the Settlement resource
        $paymentInstruction = $settlementAutoCreateService->execute(
            accountCycle: $accountCycle,
            account: $account,
            accountCustomer: $accountCustomer,
            wallet: $wallet,
            requestVO: $requestVO->toArray(),
        );

        // Build the response data
        $responseDTO = SettlementApprovalResponseDTO::fromDomain(
            paymentInstruction: $paymentInstruction,
            wallet: $paymentInstruction->wallet,
            product: $dailySusu,
        );

        // Dispatch to SusuBox Service (Payment Service)
        $susuBoxServiceDispatcher->send(
            service: config('susubox.payment.name'),
            endpoint: 'payouts',
            payload: $responseDTO->toArray(),
        );
    }
}
