<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\IndividualSusu\DailySusu\Account;

use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Shared\Models\SusuScheme;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Support\Collection;
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
            // Fetch all daily susu accounts for the customer
            return DailySusu::query()
                ->whereHas(relation: 'account.accountCustomers', callback: function ($query) use ($customer) {
                    $query->where('customer_id', $customer->id);
                })
                ->whereHas(relation: 'account', callback: function ($query) use ($susuScheme) {
                    $query->where('susu_scheme_id', $susuScheme->id)
                        ->where('status', '!=', Statuses::CLOSED->value);
                })
                ->orderByDesc('created_at')
                ->get();
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
