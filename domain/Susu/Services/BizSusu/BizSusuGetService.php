<?php

declare(strict_types=1);

namespace Domain\Susu\Services\BizSusu;

use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Susu\Models\Account;
use Domain\Susu\Models\BizSusu;
use Illuminate\Support\Facades\Log;
use Throwable;

final class BizSusuGetService
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        Account $account,
    ): BizSusu {
        try {
            // Ensure account belongs to this customer
            if ($account->customer_id !== $customer->id) {
                throw new UnauthorisedAccessException;
            }

            // Ensure the account is for a Daily Susu scheme
            if ($account->scheme->code !== config(key: 'susubox.susu_schemes.biz_susu_code')) {
                throw new UnauthorisedAccessException;
            }

            // Return the BizSusu resource
            return $account->biz;
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in BizSusuGetService', [
                'customer' => $customer,
                'account' => $account,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException;
        }
    }
}
