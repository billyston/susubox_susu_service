<?php

declare(strict_types=1);

namespace App\Application\Susu\Actions\GoalGetterSusu;

use App\Application\Shared\Helpers\ApiResponseBuilder;
use App\Domain\Account\Services\DirectDepositStatusUpdateService;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Interface\Requests\V1\Susu\IndividualSusu\GoalGetterSusu\GoalGetterSusuDirectDepositCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuDirectDepositCancelAction
{
    private DirectDepositStatusUpdateService $directDepositStatusUpdateService;

    public function __construct(
        DirectDepositStatusUpdateService $directDepositStatusUpdateService
    ) {
        $this->directDepositStatusUpdateService = $directDepositStatusUpdateService;
    }

    /**
     * @throws SystemFailureException
     */
    public function execute(
        Customer $customer,
        GoalGetterSusu $goal_getter_susu,
        GoalGetterSusuDirectDepositCancelRequest $request
    ): JsonResponse {
        // Execute the DirectDepositStatusUpdateService and return the DirectDeposit resource
        $this->directDepositStatusUpdateService->execute(
            status: Statuses::CANCELLED->value,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The direct deposit process was canceled successfully.',
        );
    }
}
