<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\BizSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Services\BizSusu\BizSusuCancelService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuCancelAction
{
    private BizSusuCancelService $bizSusuCancelService;

    /**
     * @param BizSusuCancelService $bizSusuCancelService
     */
    public function __construct(
        BizSusuCancelService $bizSusuCancelService
    ) {
        $this->bizSusuCancelService = $bizSusuCancelService;
    }

    /**
     * @param BizSusu $bizSusu
     * @return JsonResponse
     * @throws CancellationNotAllowedException
     * @throws SystemFailureException
     */
    public function execute(
        BizSusu $bizSusu,
    ): JsonResponse {
        // Execute the BizSusuCancelService
        $this->bizSusuCancelService->execute(
            bizSusu: $bizSusu,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The biz susu account setup has been cancelled successfully.'
        );
    }
}
