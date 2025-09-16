<?php

declare(strict_types=1);

namespace Domain\Shared\Actions;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureExec;
use Domain\Shared\Data\SusuSchemeResource;
use Domain\Shared\Services\SusuSchemesService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SusuSchemesAction
{
    private SusuSchemesService $susuSchemesService;

    public function __construct(
        SusuSchemesService $susuSchemesService
    ) {
        $this->susuSchemesService = $susuSchemesService;
    }

    /**
     * @throws SystemFailureExec
     */
    public function execute(
        Request $request,
    ): JsonResponse {
        $linked_wallets = $this->susuSchemesService->execute();

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful',
            description: '',
            data: SusuSchemeResource::collection(
                resource: $linked_wallets,
            ),
        );
    }
}
