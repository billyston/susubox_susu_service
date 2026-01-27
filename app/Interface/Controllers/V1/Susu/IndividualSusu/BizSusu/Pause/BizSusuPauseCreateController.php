<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\Pause;

use App\Application\Susu\Actions\IndividualSusu\BizSusu\Pause\BizSusuPauseCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\IndividualSusu\BizSusu\Pause\BizSusuPauseCreateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuPauseCreateController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param BizSusuPauseCreateRequest $bizSusuAccountPauseCreateRequest
     * @param BizSusuPauseCreateAction $bizSusuAccountPauseCreateAction
     * @return JsonResponse
     * @throws SystemFailureException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        BizSusuPauseCreateRequest $bizSusuAccountPauseCreateRequest,
        BizSusuPauseCreateAction $bizSusuAccountPauseCreateAction
    ): JsonResponse {
        // Execute the BizSusuPauseCreateAction and return the JsonResponse
        return $bizSusuAccountPauseCreateAction->execute(
            bizSusu: $bizSusu,
            request: $bizSusuAccountPauseCreateRequest->validated()
        );
    }
}
