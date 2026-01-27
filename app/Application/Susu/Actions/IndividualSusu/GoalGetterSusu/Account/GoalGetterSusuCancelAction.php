<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\IndividualSusu\GoalGetterSusu\Account;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Susu\Services\IndividualSusu\GoalGetterSusu\Account\GoalGetterSusuCancelService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuCancelAction
{
    private GoalGetterSusuCancelService $goalGetterSusuCancelService;

    /**
     * @param GoalGetterSusuCancelService $goalGetterSusuCancelService
     */
    public function __construct(
        GoalGetterSusuCancelService $goalGetterSusuCancelService
    ) {
        $this->goalGetterSusuCancelService = $goalGetterSusuCancelService;
    }

    /**
     * @param GoalGetterSusu $goalGetterSusu
     * @return JsonResponse
     * @throws CancellationNotAllowedException
     * @throws SystemFailureException
     */
    public function execute(
        GoalGetterSusu $goalGetterSusu,
    ): JsonResponse {
        // Execute the GoalGetterSusuCancelService
        $this->goalGetterSusuCancelService->execute(
            goalGetterSusu: $goalGetterSusu,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The goal getter susu account setup has been cancelled successfully.'
        );
    }
}
