<?php

namespace App\Services\SusuBox\Http\Requests;

use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;

final readonly class AccountPauseNotificationRequestHandler
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
        // Endpoint with parameter (e.g. account_pause/123)
        $endpoint = 'accounts/account-pause';

        // Send the request to the chosen service
        return $this->dispatcher->send(
            service: $service,
            endpoint: $endpoint,
            payload: $data
        );
    }
}
