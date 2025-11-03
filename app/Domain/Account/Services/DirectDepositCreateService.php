<?php

declare(strict_types=1);

namespace App\Domain\Account\Services;

use App\Application\Account\DTOs\DirectDepositCreateDTO;
use App\Application\Account\ValueObjects\DirectDepositValueObject;
use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\DirectDeposit;
use App\Domain\Shared\Exceptions\SystemFailureException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DirectDepositCreateService
{
    /**
     * @throws SystemFailureException
     */
    public static function execute(
        Account $account,
        DirectDepositCreateDTO $dto,
        DirectDepositValueObject $deposit_values,
    ): DirectDeposit {
        try {
            // Execute the database transaction
            return DB::transaction(function () use (
                $account,
                $dto,
                $deposit_values,
            ) {
                // Create the payment
                return DirectDeposit::create([
                    'account_id' => $account->id,
                    'frequencies' => $dto->frequencies ?? 0,
                    'deposited_in' => $dto->deposit_type,
                    'amount' => $deposit_values->depositAmount(),
                    'charge' => $deposit_values->charges(),
                    'total' => $deposit_values->total(),
                    'accepted_terms' => true,
                ]);
            });
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in DirectDepositCreateService', [
                'account' => $account,
                'dto' => $dto,
                'deposit_values' => $deposit_values,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was an error while trying to create the direct deposit.',
            );
        }
    }
}
