<?php

declare(strict_types=1);

namespace App\Interface\Controllers\V1\Susu\BizSusu;

use App\Application\Susu\Actions\BizSusu\BizSusuShowAction;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\BizSusu;
use App\Interface\Controllers\Shared\Controller;
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
