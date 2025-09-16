<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Resources;

use App\Exceptions\Common\SystemFailureExec;
use App\Http\Controllers\Controller;
use Domain\Shared\Actions\FrequenciesAction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FrequenciesController extends Controller
{
    /**
     * @throws SystemFailureExec
     */
    public function __invoke(
        Request $request,
        FrequenciesAction $frequenciesAction
    ): JsonResponse {
        // Execute the FrequenciesAction and return the JsonResponse
        return $frequenciesAction->execute(
            request: $request,
        );
    }
}
