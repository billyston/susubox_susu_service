<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\AccountFetch;

use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Shared\Models\SusuScheme;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuIndexService
{
    /**
     * @param Customer $customer
     * @param SusuScheme $susuScheme
     * @return Collection
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        SusuScheme $susuScheme,
    ): Collection {
        try {
            return DB::transaction(function () use (
                $customer,
                $susuScheme
            ) {
                // Ensure this is specifically a Daily Susu scheme
                if ($susuScheme->code !== config(key: 'susubox.susu_schemes.daily_susu_code')) {
                    throw new UnauthorisedAccessException(
                        message: 'The provided scheme is not a Daily Susu scheme.'
                    );
                }

                // Fetch all daily susu accounts for the customer
                return DailySusu::query()
                    ->whereHas('individual', function ($query) use ($customer, $susuScheme) {
                        $query
                            ->where('customer_id', $customer->id)
                            ->where('susu_scheme_id', $susuScheme->id);
                    })
                    ->get();
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
                'susu_scheme' => $susuScheme,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the daily susu accounts.',
            );
        }
    }
}
