<?php

namespace App\Application\Customer\Commands;

use Illuminate\Console\Command;

final class CustomerRedisStreamConsumer extends Command
{
    protected $signature = 'redis:consumer';
    protected $description = 'Consume customer data from Redis Stream';

    /**
     * @return void
     */
    public function handle(
    ): void {
        // ..
    }
}
