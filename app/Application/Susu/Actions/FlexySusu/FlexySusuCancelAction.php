<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\FlexySusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Susu\Services\FlexySusu\FlexySusuCancelService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FlexySusuCancelAction
{
    private FlexySusuCancelService $flexySusuCancelService;

    /**
     * @param FlexySusuCancelService $accountCancelService
     */
    public function __construct(
        FlexySusuCancelService $accountCancelService
    ) {
        $this->flexySusuCancelService = $accountCancelService;
    }

    /**
     * @param FlexySusu $flexySusu
     * @return JsonResponse
     * @throws CancellationNotAllowedException
     * @throws SystemFailureException
     */
    public function execute(
        FlexySusu $flexySusu,
    ): JsonResponse {
        // Execute the AccountCancelService
        $this->flexySusuCancelService->execute(
            flexySusu: $flexySusu,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The flexy susu account setup has been cancelled successfully.'
        );
    }
}
