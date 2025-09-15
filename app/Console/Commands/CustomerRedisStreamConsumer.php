<?php

namespace App\Console\Commands;

use Domain\Customer\Consumers\Customer\CustomerCreateConsumer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Throwable;

final class CustomerRedisStreamConsumer extends Command
{
    protected $signature = 'redis:consumer';
    protected $description = 'Consume customer data from Redis Stream';

    public function handle(
        CustomerCreateConsumer $customerCreateConsumer,
    ): void {
        $group = 'customer_service_group';
        $consumer = 'customer_service_consumer';

        try {
            Redis::xgroup('CREATE', 'customers_stream', $group, '0', true);
        } catch (Throwable $throwable) {
            // Log the full exception with context
            Log::error('Exception in CustomerRedisStreamConsumer', [
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);
        }

        while (true) {
            $messages = Redis::xreadgroup($group, $consumer, ['customers_stream' => '>'], 1, 5000);

            if ($messages) {
                foreach ($messages as $events) {
                    foreach ($events as $id => $data) {
                        logger($data);
                        $customerCreateConsumer->execute($data);
                        Redis::xack('customers_stream', $group, [$id]);
                    }
                }
            }
        }
    }
}
