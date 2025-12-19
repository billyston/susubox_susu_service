<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Shared;

use App\Application\Shared\Actions\StartDatesAction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class StartDatesController extends Controller
{
    /**
     * @param Request $request
     * @param StartDatesAction $startDatesAction
     * @return JsonResponse
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
