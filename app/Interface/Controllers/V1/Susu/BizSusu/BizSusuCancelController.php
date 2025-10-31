<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\BizSusu\BizSusuCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuCancelController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws CancellationNotAllowedException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $biz_susu,
        BizSusuCancelRequest $bizSusuCancelRequest,
        BizSusuCancelAction $bizSusuCancelAction
    ): JsonResponse {
        // Execute the BizSusuCancelAction
        return $bizSusuCancelAction->execute(
            customer: $customer,
            biz_susu: $biz_susu,
            bizSusuCancelRequest: $bizSusuCancelRequest
        );
    }
}
