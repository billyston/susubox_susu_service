<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Shared;

use App\Application\Shared\Actions\PingAction;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class PingController extends Controller
{
    /**
     * @param Request $request
     * @param PingAction $pingAction
     * @return JsonResponse
     */
    public function __invoke(
        Request $request,
        PingAction $pingAction
    ): JsonResponse {
        // Execute the PingAction and return the JsonResponse
        return $pingAction->execute();
    }
}
