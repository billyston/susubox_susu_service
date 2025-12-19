<?php

namespace App\Services\SusuBox\Http\Requests;

use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;

final readonly class WithdrawalRequestHandler
{
    /**
     * @param SusuBoxServiceDispatcher $dispatcher
     */
    public function __construct(
        private SusuBoxServiceDispatcher $dispatcher
    ) {
        // ..
    }

    /**
     * @param string $service
     * @param array $data
     * @return array
     */
    public function sendToService(
        string $service,
        array $data
    ): array {
        // Endpoint with parameter (e.g. withdrawals/123/notify)
        $endpoint = "withdrawals/{$data['debit_id']}/notify";

        // Send the request to the chosen service
        return $this->dispatcher->send(
            service: $service,
            endpoint: $endpoint,
            payload: $data
        );
    }
}
