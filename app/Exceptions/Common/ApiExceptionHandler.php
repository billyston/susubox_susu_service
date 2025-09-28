<?php

declare(strict_types=1);

namespace App\Exceptions\Common;

use Domain\Customer\Exceptions\LinkedWalletNotFoundException;
use Domain\Shared\Exceptions\FrequencyNotFoundException;
use Domain\Shared\Exceptions\SusuSchemeNotFoundException;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

final class ApiExceptionHandler extends ExceptionHandler
{
    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render(
        $request,
        Throwable $e
    ): JsonResponse {
        $exceptions = [
            ThrottleRequestsException::class => [Response::HTTP_TOO_MANY_REQUESTS, 'Too many requests.', 'You have made too many requests.'],
            UnauthorisedAccessException::class => [Response::HTTP_FORBIDDEN, 'Request forbidden.', 'You are forbidden to perform this action.'],
            LinkedWalletNotFoundException::class => [Response::HTTP_NOT_FOUND, 'Resource not found.', 'The wallet was not found.'],
            SusuSchemeNotFoundException::class => [Response::HTTP_NOT_FOUND, 'Resource not found.', 'The susu scheme was not found.'],
            FrequencyNotFoundException::class => [Response::HTTP_NOT_FOUND, 'Resource not found.', 'The frequency was not found.'],
            ModelNotFoundException::class => [Response::HTTP_NOT_FOUND, 'Resource not found.', 'The requested resource does not exist.'],
            NotFoundHttpException::class => [Response::HTTP_NOT_FOUND, 'Endpoint not found.', 'The requested endpoint does not exist.'],
            InvalidArgumentException::class => [Response::HTTP_UNPROCESSABLE_ENTITY, 'Request unprocessable.', 'An unexpected error occurred.'],
            ValidationException::class => [Response::HTTP_UNPROCESSABLE_ENTITY, 'Request unprocessable.', 'An unexpected error occurred.'],
            MethodNotAllowedHttpException::class => [Response::HTTP_METHOD_NOT_ALLOWED, 'Method not allowed.', 'This HTTP method is not supported.'],
            QueryException::class => [Response::HTTP_BAD_REQUEST, 'Database error.', 'Database error occurred.'],
            RelationNotFoundException::class => [Response::HTTP_INTERNAL_SERVER_ERROR, 'Relationship error.', 'A required relationship is missing.'],
            AuthenticationException::class => [Response::HTTP_UNAUTHORIZED, 'Request unauthenticated.', 'You are not logged in.'],
            AuthorizationException::class => [Response::HTTP_FORBIDDEN, 'Request unauthorized.', 'You do not have permission for this action.'],
            AccessDeniedHttpException::class => [Response::HTTP_FORBIDDEN, 'Request denied.', 'You are not allowed to access this resource.'],
        ];

        foreach ($exceptions as $exceptionType => [$code, $message, $description]) {
            if ($e instanceof $exceptionType) {
                return $this->formatErrorResponse($code, $message, $description, $this->extractErrorDetails($e));
            }
        }

        // Fallback for unhandled exceptions
        return $this->formatErrorResponse(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            'Request unprocessable.',
            'An unexpected error occurred.',
            [$e->getMessage()]
        );
    }

    public function register(): void
    {
        $this->reportable(
            function (
                Throwable $e
            ): void {
                //..
            }
        );
    }

    private function formatErrorResponse(
        int $code,
        string $message,
        string $description,
        array $errors = []
    ): JsonResponse {
        return new JsonResponse(
            [
                'version' => '1.0',
                'status' => false,
                'code' => $code,
                'message' => $message,
                'description' => $description,
                'errors' => $errors,
            ],
            $code
        );
    }

    private function extractErrorDetails(
        Throwable $e
    ): array {
        if ($e instanceof ValidationException) {
            return collect($e->errors())->flatten()->values()->all();
        }

        if ($e instanceof NotFoundHttpException && $e->getPrevious() instanceof ModelNotFoundException) {
            return [$e->getPrevious()->getMessage()];
        }

        // Handle your custom exception
        if ($e instanceof LinkedWalletNotFoundException) {
            return [$e->getMessage()];
        }

        if ($e instanceof SusuSchemeNotFoundException) {
            return [$e->getMessage()];
        }

        if ($e instanceof FrequencyNotFoundException) {
            return [$e->getMessage()];
        }

        if ($e instanceof UnauthorisedAccessException) {
            return [$e->getMessage()];
        }

        return [$e->getMessage()];
    }
}
