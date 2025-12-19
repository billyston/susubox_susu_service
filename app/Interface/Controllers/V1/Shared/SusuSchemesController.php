<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Shared;

use App\Application\Shared\Actions\SusuSchemesAction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class SusuSchemesController extends Controller
{
    /**
     * @param Request $request
     * @param SusuSchemesAction $susuSchemesAction
     * @return JsonResponse
     * @throws SystemFailureException
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
