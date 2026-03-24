<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Cycle;

use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DailySusuCycleIndexService
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
                $dailySusu,
                $customer,
            ) {
                // Extract the main resources
                $account = $dailySusu->account;
                $accountCustomer = $account->accountCustomer->customer;

                // Ensure the customer making the request is the owner of the account
                if ($accountCustomer->id !== $customer->id) {
                    throw new UnauthorisedAccessException(
                        message: 'You are not authorised to access these account cycles.'
                    );
                }

                // Fetch all account cycles that belong to the account
                return $account->accountCycles()
                    ->orderByDesc('created_at')
                    ->get();
            });
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DailySusuCycleIndexService', [
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
