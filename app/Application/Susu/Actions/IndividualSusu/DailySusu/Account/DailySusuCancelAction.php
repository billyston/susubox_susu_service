<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\DailySusu\Account;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Services\IndividualSusu\DailySusu\Account\DailySusuCancelService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DailySusuCancelAction
{
    private DailySusuCancelService $dailySusuCancelService;

    /**
     * @param DailySusuCancelService $dailySusuCancelService
     */
    public function __construct(
        DailySusuCancelService $dailySusuCancelService
    ) {
        $this->dailySusuCancelService = $dailySusuCancelService;
    }

    /**
     * @param DailySusu $dailySusu
     * @return JsonResponse
     * @throws CancellationNotAllowedException
     * @throws SystemFailureException
     */
    public function execute(
        DailySusu $dailySusu,
    ): JsonResponse {
        // Execute the DailySusuCancelService
        $this->dailySusuCancelService->execute(
            dailySusu: $dailySusu,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The daily susu account setup has been cancelled successfully.'
        );
    }
}
