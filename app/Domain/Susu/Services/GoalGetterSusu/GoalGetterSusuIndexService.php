<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\GoalGetterSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Shared\Models\SusuScheme;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class GoalGetterSusuIndexService
{
    /**
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        SusuScheme $susu_scheme,
    ): Collection {
        try {
            return DB::transaction(function () use (
                $customer,
                $susu_scheme
            ) {
                // Ensure this is specifically a Daily Susu scheme
                if ($susu_scheme->code !== config(key: 'susubox.susu_schemes.goal_getter_susu_code')) {
                    throw new UnauthorisedAccessException(
                        message: 'The provided scheme is not a Goal Getter Susu scheme.'
                    );
                }

                // Fetch all daily susu accounts for the customer
                $accounts = Account::where('customer_id', $customer->id)
                    ->where('susu_scheme_id', $susu_scheme->id)
                    ->with('goal')
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Map to only return the related DailySusu models
                return $accounts->map(function ($account) {
                    return $account->goal;
                })->filter();
            });
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            QueryException $queryException
        ) {
            throw $queryException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuIndexService', [
                'customer' => $customer,
                'susu_scheme' => $susu_scheme,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'A system failure occurred while fetching the goal getter susu accounts.'
            );
        }
    }
}
