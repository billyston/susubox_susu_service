<?php

declare(strict_types=1);

namespace App\Application\Shared\Actions;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Services\SusuSchemesService;
use App\Interface\Resources\V1\Shared\SusuSchemeResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SusuSchemesAction
{
    private SusuSchemesService $susuSchemesService;

    /**
     * @param SusuSchemesService $susuSchemesService
     */
    public function __construct(
        SusuSchemesService $susuSchemesService
    ) {
        $this->susuSchemesService = $susuSchemesService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Request $request,
    ): JsonResponse {
        // Execute the SusuSchemesService and return the Collection
        $susuSchemes = $this->susuSchemesService->execute();

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful',
            description: '',
            data: SusuSchemeResource::collection(
                resource: $susuSchemes,
            ),
        );
    }
}
