<?php

declare(strict_types=1);

namespace App\Application\Account\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class AccountCycleCompletedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @param string $accountCycleResourceId
     */
    public function __construct(
        public readonly string $accountCycleResourceId
    ) {
        //..
    }
}
