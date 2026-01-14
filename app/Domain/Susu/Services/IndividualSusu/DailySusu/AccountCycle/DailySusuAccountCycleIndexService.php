<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\AccountCycle;

use App\Domain\Account\Models\AccountCycle;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuAccountCycleIndexService
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @return Collection
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        DailySusu $dailySusu,
    ): Collection {
        try {
            return DB::transaction(function () use (
                $customer,
                $dailySusu,
            ) {
                /**
                 * AUTHORISATION GUARD
                 * Ensure the DailySusu belongs to the customer
                 */
                $belongsToCustomer = $dailySusu->individual->customer_id === $customer->id;
                if (! $belongsToCustomer) {
                    throw new UnauthorisedAccessException(
                        message: 'You are not authorised to access these account cycles.'
                    );
                }

                /**
                 * Fetch AccountCycles for this DailySusu
                 */
                return AccountCycle::query()
                    ->where('cycleable_type', DailySusu::class)
                    ->where('cycleable_id', $dailySusu->id)
                    ->orderBy('cycle_number', 'desc')
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
            Log::error('Exception in DailySusuAccountCycleIndexService', [
                'customer' => $customer,
                'daily_susu' => $dailySusu,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the daily susu account cycles.',
            );
        }
    }
}
