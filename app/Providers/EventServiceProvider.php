<?php

declare(strict_types=1);

namespace App\Providers;

use App\Application\Account\Events\AccountCycleCompletedEvent;
use App\Application\Account\Listeners\AccountCycleCompletedListener;
use App\Application\Susu\Listeners\DailySusuAutoSettlementListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // AccountCycleCompletedEvent and Listeners
        AccountCycleCompletedEvent::class => [
            AccountCycleCompletedListener::class,
            DailySusuAutoSettlementListener::class,
        ],
    ];

    /**
     * @return void
     */
    public function boot(
    ): void {
        // ..
    }

    /**
     * @return bool
     */
    public function shouldDiscoverEvents(
    ): bool {
        return false; // Set to false since you're manually registering
    }
}
