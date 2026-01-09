<?php

declare(strict_types=1);

namespace App\Application\Susu\Listeners;

use App\Application\Account\Events\AccountCycleCompletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

final class DailySusuAutoSettlementListener implements ShouldQueue
{
    public function __construct(
    ) {
        //..
    }

    public function handle(
        AccountCycleCompletedEvent $accountCycleCompletedEvent
    ): void {
        logger()->info([$accountCycleCompletedEvent]);
    }
}
