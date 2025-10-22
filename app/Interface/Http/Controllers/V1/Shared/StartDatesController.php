<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Shared;

use App\Application\Shared\Actions\StartDatesAction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class StartDatesController extends Controller
{
    /**
     * @throws SystemFailureException
     */
    public function __invoke(
        Request $request,
        StartDatesAction $startDatesAction
    ): JsonResponse {
        // Execute the StartDatesAction and return the JsonResponse
        return $startDatesAction->execute(
            request: $request,
        );
    }
}
