<?php

declare(strict_types=1);

namespace Domain\Susu\Actions\BizSusu;

use App\Common\Helpers\ApiResponseBuilder;
use App\Exceptions\Common\SystemFailureException;
use App\Http\Requests\V1\Susu\BizSusu\BizSusuCancelRequest;
use Domain\Customer\Models\Customer;
use Domain\Susu\Exceptions\Account\CancellationNotAllowedException;
use Domain\Susu\Models\BizSusu;
use Domain\Susu\Services\Account\AccountCancelService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BizSusuCancelAction
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
        BizSusu $biz_susu,
        BizSusuCancelRequest $bizSusuCancelRequest,
    ): JsonResponse {
        // Execute the AccountCancelService
        $this->accountCancelService->execute(
            account: $biz_susu->account,
        );

        // Build and return the JsonResponse
        return ApiResponseBuilder::success(
            code: Response::HTTP_OK,
            message: 'Request successful.',
            description: 'The biz susu account setup has been cancelled.'
        );
    }
}
