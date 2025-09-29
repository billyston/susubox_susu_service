<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\GoalGetterSusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use App\Http\Requests\V1\Susu\GoalGetterSusu\GoalGetterSusuCancelRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Exceptions\Account\CancellationNotAllowedException;
use Domain\Susu\Models\Account;
use Domain\Susu\Services\Account\AccountCancelService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GoalGetterSusuCancelAction
{
    private AccountCancelService $accountCancelService;

    public function __construct(
        AccountCancelService $accountCancelService
    ) {
        $this->accountCancelService = $accountCancelService;
    }

    /**
     * @throws SystemFailureException
     * @throws CancellationNotAllowedException
     */
    public function execute(
        Customer $customer,
        Account $account,
        GoalGetterSusuCancelRequest $goalGetterSusuCancelRequest,
    ): JsonResponse {
        // Execute the AccountCancelService
        $this->accountCancelService->execute(
            account: $account,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The goal getter susu account setup has been cancelled.'
        );
    }
}
