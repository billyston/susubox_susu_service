<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Shared;

use App\Application\Shared\Actions\FrequenciesAction;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Interface\Controllers\Shared\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FrequenciesController extends Controller
{
    /**
     * @param Request $request
     * @param FrequenciesAction $frequenciesAction
     * @return JsonResponse
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
