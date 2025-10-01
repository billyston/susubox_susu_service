<?php

declare(strict_types=1);

namespace Domain\Susu\Services\DailySusu;

use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Shared\Models\SusuScheme;
use Domain\Susu\Models\Account;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuIndexService
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
                if ($susu_scheme->code !== config(key: 'susubox.susu_schemes.daily_susu_code')) {
                    throw new UnauthorisedAccessException(
                        message: 'The provided scheme is not a Daily Susu scheme.'
                    );
                }

                // Fetch all daily susu accounts for the customer
                $accounts = Account::where('customer_id', $customer->id)
                    ->where('susu_scheme_id', $susu_scheme->id)
                    ->with('daily')
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Map to only return the related DailySusu models
                return $accounts->map(function ($account) {
                    return $account->daily;
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
                message: 'A system failure occurred while fetching the daily susu accounts.'
            );
        }
    }
}
