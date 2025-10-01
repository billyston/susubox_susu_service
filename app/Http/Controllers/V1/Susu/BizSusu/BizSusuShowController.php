<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Susu\BizSusu;

use App\Exceptions\Common\SystemFailureException;
use App\Http\Controllers\Controller;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Actions\BizSusu\BizSusuShowAction;
use Domain\Susu\Models\BizSusu;
use Symfony\Component\HttpFoundation\JsonResponse;

final class BizSusuShowController extends Controller
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function __invoke(
        Customer $customer,
        BizSusu $biz_susu,
        BizSusuShowAction $bizSusuShowAction
    ): JsonResponse {
        // Execute the BizSusuShowAction and return the JsonResponse
        return $bizSusuShowAction->execute(
            customer: $customer,
            biz_susu: $biz_susu,
        );
    }
}
