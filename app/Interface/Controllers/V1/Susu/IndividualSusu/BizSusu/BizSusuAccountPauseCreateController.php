<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuAccountPauseCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\BizSusuAccountPauseCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuAccountPauseCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param BizSusuAccountPauseCreateRequest $bizSusuAccountPauseCreateRequest
     * @param BizSusuAccountPauseCreateAction $bizSusuAccountPauseCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        BizSusuAccountPauseCreateRequest $bizSusuAccountPauseCreateRequest,
        BizSusuAccountPauseCreateAction $bizSusuAccountPauseCreateAction
    ): JsonResponse {
        // Execute the BizSusuAccountPauseCreateAction and return the JsonResponse
        return $bizSusuAccountPauseCreateAction->execute(
            bizSusu: $bizSusu,
            request: $bizSusuAccountPauseCreateRequest->validated()
        );
    }
}
