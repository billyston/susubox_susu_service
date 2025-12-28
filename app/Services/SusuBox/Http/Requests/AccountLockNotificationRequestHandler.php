<?php

namespace App\Services\SusuBox\Http\Requests;

use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;

final readonly class AccountLockNotificationRequestHandler
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
    public function sendToSusuBoxService(
        string $service,
        array $data
    ): array {
        // Endpoint with parameter (e.g. account_lock/123)
        $endpoint = 'accounts/account-lock';

        // Send the request to the chosen service
        return $this->dispatcher->send(
            service: $service,
            endpoint: $endpoint,
            payload: $data
        );
    }
}
