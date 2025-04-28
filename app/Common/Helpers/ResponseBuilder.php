<?php

declare(strict_types=1);

namespace App\Common\Helpers;

use Symfony\Component\HttpFoundation\JsonResponse;

final class ResponseBuilder
{
    public static function success(
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
            'data' => $data,
        ]);
    }

    public static function error(
        bool $status,
        int $code,
        string $message,
        ?string $description = null
    ): JsonResponse {
        return response()->json([
            'version' => '1.0',
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'description' => $description,
        ]);
    }

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
            'data' => data_get(target: $data, key: 'data'),
            'data_meta' => data_get(target: $data, key: 'meta'),
        ]);
    }

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
