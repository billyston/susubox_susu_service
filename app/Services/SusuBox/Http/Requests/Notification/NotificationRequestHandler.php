<?php

namespace App\Services\SusuBox\Http\Requests\Notification;

use App\Services\SusuBox\Http\SusuBoxServiceDispatcher;

final readonly class NotificationRequestHandler
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
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function sendToSusuBoxService(
        string $service,
        string $endpoint,
        array $data
    ): array {
        // Send the request to the chosen service
        return $this->dispatcher->send(
            service: $service,
            endpoint: $endpoint,
            payload: $data
        );
    }
}
