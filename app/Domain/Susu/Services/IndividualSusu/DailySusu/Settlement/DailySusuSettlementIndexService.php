<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Settlement;

use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuSettlementIndexService
{
    /**
     * @param Customer $customer
     * @param DailySusu $dailySusu
     * @return Collection
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public function execute(
        Customer $customer,
        DailySusu $dailySusu,
    ): Collection {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $customer,
                $dailySusu
            ) {
                // Guard and throw UnauthorisedAccessException
                if ($dailySusu->individual->customer->id !== $customer->id) {
                    throw new UnauthorisedAccessException(
                        message: 'You are not authorized to perform this action.'
                    );
                }

                // Return the AccountSettlement collection
                return $dailySusu->account->settlements;
            });
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuSettlementIndexService', [
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
                message: 'There was a system failure while trying to fetch the settlements.',
            );
        }
    }
}
