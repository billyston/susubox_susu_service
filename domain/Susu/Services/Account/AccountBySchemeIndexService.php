<?php

declare(strict_types=1);

namespace Domain\Susu\Services\Account;

use App\Exceptions\Common\SystemFailureException;
use Domain\Customer\Models\Customer;
use Domain\Shared\Exceptions\UnauthorisedAccessException;
use Domain\Shared\Models\SusuScheme;
use Domain\Susu\Models\Account;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountBySchemeIndexService
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
                // Get all $susu_scheme that belongs to $customer
                return Account::where(
                    'customer_id',
                    $customer->id
                )->where(
                    'susu_scheme_id',
                    $susu_scheme->id
                )->orderBy(
                    'created_at',
                    'desc'
                )->get();
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
            Log::error('Exception in AccountBySchemeIndexService', [
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
                message: 'A system failure occurred while fetch the daily susu accounts.'
            );
        }
    }
}
