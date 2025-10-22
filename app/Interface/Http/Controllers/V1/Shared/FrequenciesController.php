<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Shared;

use App\Application\Shared\Actions\FrequenciesAction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FrequenciesController extends Controller
{
    /**
     * @throws SystemFailureException
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
