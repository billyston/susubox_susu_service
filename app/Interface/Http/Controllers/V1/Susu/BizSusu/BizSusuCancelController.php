<?php

declare(strict_types=1);

namespace App\Interface\Http\Controllers\V1\Susu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\BizSusu;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Requests\V1\Susu\BizSusu\BizSusuCancelRequest;
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
        // Execute the BizSusuCancelAction and return the JsonResponse
        return $bizSusuCancelAction->execute(
            customer: $customer,
            biz_susu: $biz_susu,
            bizSusuCancelRequest: $bizSusuCancelRequest
        );
    }
}
