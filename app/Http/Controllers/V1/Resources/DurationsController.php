<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Resources;

use App\Exceptions\Common\SystemFailureExec;
use App\Http\Controllers\Controller;
use Domain\Shared\Actions\DurationsAction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DurationsController extends Controller
{
    /**
     * @throws SystemFailureExec
     */
    public function __invoke(
        Request $request,
        DurationsAction $durationsAction
    ): JsonResponse {
        // Execute the DurationsAction and return the JsonResponse
        return $durationsAction->execute(
            request: $request,
        );
    }
}
