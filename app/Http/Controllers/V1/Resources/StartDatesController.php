<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Resources;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Shared\Actions\StartDatesAction;
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
