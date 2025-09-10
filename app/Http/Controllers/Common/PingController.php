<?php

declare(strict_types=1);

namespace App\Http\Controllers\Common;

use App\Common\Actions\Ping\PingAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class PingController extends Controller
{
    public function __invoke(
        Request $request,
        PingAction $pingAction
    ): JsonResponse {
        // Execute the PingAction and return the JsonResponse
        return $pingAction->execute();
    }
}
