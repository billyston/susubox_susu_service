<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuDirectDepositCreateAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use App\Interface\Requests\V1\Susu\BizSusu\BizSusuDirectDepositCreateRequest;
use Brick\Money\Exception\UnknownCurrencyException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuDirectDepositCreateController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnknownCurrencyException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $biz_susu,
        BizSusuDirectDepositCreateRequest $bizSusuDirectDepositCreateRequest,
        BizSusuDirectDepositCreateAction $bizSusuDirectDepositCreateAction
    ): JsonResponse {
        // Execute the BizSusuDirectDepositCreateAction and return the JsonResponse
        return $bizSusuDirectDepositCreateAction->execute(
            customer: $customer,
            biz_susu: $biz_susu,
            request: $bizSusuDirectDepositCreateRequest
        );
    }
}
