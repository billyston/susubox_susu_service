<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Resources;

use App\Exceptions\Common\SystemFailureExec;
use App\Http\Controllers\Controller;
use Domain\Shared\Actions\SusuSchemesAction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class SusuSchemesController extends Controller
{
    /**
     * @throws SystemFailureExec
     */
    public function __invoke(
        Request $request,
        SusuSchemesAction $susuSchemesAction
    ): JsonResponse {
        // Execute the SusuSchemesAction and return the JsonResponse
        return $susuSchemesAction->execute(
            request: $request,
        );
    }
}
