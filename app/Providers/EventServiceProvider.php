<?php

declare(strict_types=1);

namespace App\Providers;

use App\Application\Account\Events\AccountCycleCompletedEvent;
use App\Application\Account\Listeners\AccountCycleCompletedListener;
use App\Application\Transaction\Events\TransactionCreditCreatedEvent;
use App\Application\Transaction\Events\TransactionDebitCreatedEvent;
use App\Application\Transaction\Listeners\TransactionAccountStatusUpdateListener;
use App\Application\Transaction\Listeners\TransactionCreditCompletedListener;
use App\Application\Transaction\Listeners\TransactionDebitCompletedListener;
use App\Application\Transaction\Listeners\TransactionNotificationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // AccountCycleCompletedEvent and Listeners
        AccountCycleCompletedEvent::class => [
            AccountCycleCompletedListener::class,
        ],

        // TransactionCreditCreatedEvent / TransactionDebitCreatedEvent and Listeners
        TransactionCreditCreatedEvent::class => [
            TransactionAccountStatusUpdateListener::class,
            TransactionCreditCompletedListener::class,
            TransactionNotificationListener::class,
        ],
        TransactionDebitCreatedEvent::class => [
            TransactionAccountStatusUpdateListener::class,
            TransactionDebitCompletedListener::class,
            TransactionNotificationListener::class,
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
