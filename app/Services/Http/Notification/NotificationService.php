<?php

declare(strict_types=1);

namespace App\Services\Http\Notification;

class NotificationService
{
    public string $base_url;
    public string $app_id;
    public string $app_key;
    public array $headers;

    public function __construct(
    ) {
        $this->base_url = config(key: 'susubox.notification.base_url');
        $this->app_id = config(key: 'susubox.notification.app_id');
        $this->app_key = config(key: 'susubox.notification.app_key');
        $this->headers = [
            'Content-Type' => 'application/vnd.api+json',
            'Accept' => 'application/vnd.api+json',
            'X-App-Id' => $this->app_id,
            'X-App-Key' => $this->app_key,
        ];
    }
}
