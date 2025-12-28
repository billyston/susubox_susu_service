<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuCancelAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\CancellationNotAllowedException;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuCancelRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuCancelController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param BizSusuCancelRequest $bizSusuCancelRequest
     * @param BizSusuCancelAction $bizSusuCancelAction
     * @return JsonResponse
     * @throws CancellationNotAllowedException
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        BizSusuCancelRequest $bizSusuCancelRequest,
        BizSusuCancelAction $bizSusuCancelAction
    ): JsonResponse {
        // Execute the BizSusuCancelAction
        return $bizSusuCancelAction->execute(
            bizSusu: $bizSusu,
        );
    }
}
