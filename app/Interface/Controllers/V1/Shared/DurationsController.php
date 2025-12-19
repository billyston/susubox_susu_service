<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Shared;

use App\Application\Shared\Actions\DurationsAction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DurationsController extends Controller
{
    /**
     * @param Request $request
     * @param DurationsAction $durationsAction
     * @return JsonResponse
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
