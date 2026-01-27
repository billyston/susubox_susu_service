<?php

declare(strict_types=1);

namespace App\Application\Shared\Helpers;

use Symfony\Component\HttpFoundation\JsonResponse;

final class ApiResponseBuilder
{
    /**
     * @param bool $status
     * @param int $code
     * @param string $message
     * @param string|null $description
     * @return JsonResponse
     */
    public static function ping(
        bool $status,
        int $code,
        string $message,
        string $description = null,
    ): JsonResponse {
        return response()->json([
            'version' => '1.0',
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'description' => $description,
        ]);
    }

    /**
     * @param int $code
     * @param string|null $message
     * @param string|null $description
     * @param mixed|null $data
     * @return JsonResponse
     */
    public static function success(
        int $code,
        string $message = null,
        ?string $description = null,
        mixed $data = null,
    ): JsonResponse {
        $response = [
            'version' => '1.0',
            'status' => true,
            'code' => $code,
            'message' => $message !== null && $message !== '' && $message !== '0' ? $message : 'Request successful',
            'description' => $description,
        ];

        if (! empty($data)) {
            // Exclude the data key is empty
            $response['data'] = $data;

            // Exclude the included key if data key does not exist
            if (method_exists($data, 'included')) {
                $included = $data->included();
                if (! empty($included)) {
                    $response['included'] = $included;
                }
            }
        }

        // Return the response
        return response()->json($response);
    }

    /**
     * @param int $code
     * @param string|null $message
     * @param string|null $description
     * @param mixed|null $data
     * @return JsonResponse
     */
    public static function toArray(
        int $code,
        string $message = null,
        ?string $description = null,
        mixed $data = null,
    ): JsonResponse {
        return response()->json([
            'version' => '1.0',
            'code' => $code,
            'message' => $message,
            'description' => $description,
            'data' => $data['data'] ?? null,
        ]);
    }

    /**
     * @param int $code
     * @param string $message
     * @param string|null $description
     * @return JsonResponse
     */
    public static function error(
        int $code,
        string $message,
        ?string $description = null
    ): JsonResponse {
        return response()->json([
            'version' => '1.0',
            'status' => false,
            'code' => $code,
            'message' => $message,
            'description' => $description,
        ]);
    }

    /**
     * @param bool $status
     * @param int $code
     * @param string $message
     * @param string|null $description
     * @param mixed|null $data
     * @return JsonResponse
     */
    public static function paginated(
        bool $status,
        int $code,
        string $message,
        ?string $description = null,
        mixed $data = null
    ): JsonResponse {
        return response()->json([
            'version' => '1.0',
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'description' => $description,
            'data' => $data->items(),
            'meta' => [
                'current_page' => $data->currentPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
                'last_page'    => $data->lastPage(),
            ],
            'links' => [
                'first' => $data->url(1),
                'last'  => $data->url($data->lastPage()),
                'prev'  => $data->previousPageUrl(),
                'next'  => $data->nextPageUrl(),
            ],
        ]);
    }

    /**
     * @param bool $status
     * @param int $code
     * @param string $message
     * @param string|null $description
     * @param mixed|null $data
     * @return JsonResponse
     */
    public static function collection(
        bool $status,
        int $code,
        string $message,
        ?string $description = null,
        mixed $data = null
    ): JsonResponse {
        return response()->json([
            'version' => '1.0',
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'description' => $description,
            'data' => $data->data,
            'data_meta' => $data->meta,
        ]);
    }

    /**
     * @param bool $status
     * @param int $code
     * @param string $message
     * @param string|null $description
     * @param mixed|null $error
     * @return JsonResponse
     */
    public static function unprocessable(
        bool $status,
        int $code,
        string $message,
        ?string $description = null,
        mixed $error = null
    ): JsonResponse {
        return response()->json([
            'version' => '1.0',
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'description' => $description,
            'errors' => $error,
        ]);
    }

    /**
     * @param bool $status
     * @param int $code
     * @param string $message
     * @param mixed|null $token
     * @param mixed|null $user
     * @return JsonResponse
     */
    public static function token(
        bool $status,
        int $code,
        string $message,
        mixed $token = null,
        mixed $user = null
    ): JsonResponse {
        return response()->json([
            'version' => '1.0',
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'data' => $user,
            'token' => $token,
        ]);
    }
}
