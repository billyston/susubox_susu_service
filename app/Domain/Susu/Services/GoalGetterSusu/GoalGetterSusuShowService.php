<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\GoalGetterSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\GoalGetterSusu;
use Illuminate\Support\Facades\Log;
use Throwable;

final class GoalGetterSusuShowService
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        Account $account,
    ): GoalGetterSusu {
        try {
            // Ensure account belongs to this customer
            if ($account->customer_id !== $customer->id) {
                throw new UnauthorisedAccessException;
            }

            // Ensure the account is for a Daily Susu scheme
            if ($account->scheme->code !== config(key: 'susubox.susu_schemes.goal_getter_susu_code')) {
                throw new UnauthorisedAccessException;
            }

            // Return the GoalGetterSusu resource
            return $account->goal;
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in GoalGetterSusuShowService', [
                'customer' => $customer,
                'account' => $account,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system error occurred while trying to fetch the goal getter susu.',
            );
        }
    }
}
