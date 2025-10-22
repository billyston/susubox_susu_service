<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Shared;

use App\Application\Shared\Actions\DurationsAction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DurationsController extends Controller
{
    /**
     * @throws SystemFailureException
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
