<?php

declare(strict_types=1);

namespace App\Application\Account\Listeners;

use App\Application\Account\Events\AccountCycleCompletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

final class AccountCycleCompletedListener implements ShouldQueue
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
