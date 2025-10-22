<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\BizSusu;

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

final class BizSusuIndexService
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
                // Ensure this is specifically a Biz Susu scheme
                if ($susu_scheme->code !== config(key: 'susubox.susu_schemes.biz_susu_code')) {
                    throw new UnauthorisedAccessException(
                        message: 'The provided scheme is not a Biz Susu scheme.'
                    );
                }

                // Fetch all biz susu accounts for the customer
                $accounts = Account::where('customer_id', $customer->id)
                    ->where('susu_scheme_id', $susu_scheme->id)
                    ->with('biz')
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Map to only return the related BizSusu models
                return $accounts->map(function ($account) {
                    return $account->biz;
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
            Log::error('Exception in BizSusuIndexService', [
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
                message: 'A system failure occurred while fetching the biz susu accounts.'
            );
        }
    }
}
