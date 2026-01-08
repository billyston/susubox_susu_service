<?php

namespace App\Services\SusuBox\Http;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

final class SusuBoxServiceDispatcher
{
    /**
     * @param string $service
     * @param string $endpoint
     * @param array $payload
     * @param string $method
     * @return array
     */
    public function send(
        string $service,
        string $endpoint,
        array $payload = [],
        string $method = 'POST'
    ): array {
        // Map service names to their configured base URLs
        $baseUrls = [
            'authentication' => config('susubox.authentication.base_url'),
            'customer' => config('susubox.customer.base_url'),
            'notification' => config('susubox.notification.base_url'),
            'payment' => config('susubox.payment.base_url'),
        ];

        // Ensure service name is valid
        if (! isset($baseUrls[$service])) {
            throw new InvalidArgumentException('Unknown SusuBox service: '.$service);
        }

        // Construct full target URL
        $url = rtrim($baseUrls[$service], '/') . '/' . ltrim($endpoint, '/');

        try {
            // Send the request using Laravel's HTTP client
            $response = Http::withHeaders([
                'X-Internal-Token' => config('services.internal.token'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->{$method}($url, $payload);

            // Throw exception if status code is not 2xx
            $response->throw();

            // Return parsed JSON response
            return $response->json();
        } catch (
            RequestException $exception
        ) {
            // Log the full exception with context
            Log::error('Exception in PaymentServiceDebitRequestJob', [
                'service' => $service,
                'endpoint' => $endpoint,
                'payload' => $payload,
                'exception' => [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],
            ]);

            // Return structured error
            return [
                'success' => false,
                'error' => $exception->getMessage(),
            ];
        }
    }
}
