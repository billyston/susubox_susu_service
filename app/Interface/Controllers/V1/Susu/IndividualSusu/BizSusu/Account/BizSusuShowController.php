<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\IndividualSusu\BizSusu\Account;

use App\Application\Susu\Actions\IndividualSusu\BizSusu\Account\BizSusuShowAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Interface\Controllers\Shared\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuShowController extends Controller
{
    /**
     * @param Customer $customer
     * @param BizSusu $bizSusu
     * @param BizSusuShowAction $bizSusuShowAction
     * @return JsonResponse
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $bizSusu,
        BizSusuShowAction $bizSusuShowAction
    ): JsonResponse {
        // Execute the BizSusuShowAction and return the JsonResponse
        return $bizSusuShowAction->execute(
            customer: $customer,
            bizSusu: $bizSusu,
        );
    }
}
