<?php

namespace App\Services\SusuBox\Http\Requests;

use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;

final readonly class TransactionCreatedRequestHandler
{
    public function __construct(
        private SusuBoxServiceDispatcher $dispatcher
    ) {
        // ..
    }

    public function sendToSusuBoxService(
        string $service,
        array $data
    ) {
        // Endpoint with parameter (e.g. transactions/123)
        $endpoint = 'transactions';

        // Send the request to the chosen service
        return $this->dispatcher->send(
            service: $service,
            endpoint: $endpoint,
            payload: $data
        );
    }
}
