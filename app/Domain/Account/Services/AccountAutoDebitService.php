<?php

declare(strict_types=1);

namespace App\Domain\Account\Services;

use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AccountAutoDebitService
{
    /**
     * @param Model $model
     * @param string $initiator
     * @param Customer $customer
     * @return DailySusu
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Model $model,
        string $initiator,
        Customer $customer,
    ): DailySusu {
        try {
            // Execute the database transaction
            return DB::transaction(
                function () use (
                    $model,
                    $initiator,
                    $customer,
                ) {
                    $now = now();
                    $currentStatus = (bool) $model->auto_settlement;
                    $newStatus = ! $currentStatus;

                    // Cooldown guard (anti-abuse)
                    $lastEvent = $model->autoDebits()->latest('requested_at')->first();

                    // Throw DomainException if last update is less than 24hours
                    if ($lastEvent && $lastEvent->requested_at->gt($now->subHours(24))) {
                        throw new UnauthorisedAccessException('This action can only be made once every 24 hours. Please try again later.');
                    }

                    // Determine effective time
                    $effectiveAt = $effectiveAt ?? $now;

                    // Update the (auto_settlement) status with the $newStatus
                    $model->update([
                        'auto_settlement' => $newStatus,
                    ]);

                    // Create the AccountAutoDebit
                    $model->autoDebits()->create([
                        'action' => $newStatus ? 'enabled' : 'disabled',
                        'from_state' => $currentStatus,
                        'to_state' => $newStatus,
                        'requested_at' => $now,
                        'effective_at' => $effectiveAt,
                        'initiator' => $initiator,
                        'initiator_id' => $customer->id ?? null,
                    ]);

                    // Refresh and return the model
                    return $model->refresh();
                }
            );
        } catch (
            UnauthorisedAccessException $domainException
        ) {
            throw $domainException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in AccountAutoDebitService', [
                'model' => $model,
                'initiator' => $initiator,
                'customer' => $customer,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while updating the account auto debit status.',
            );
        }
    }
}
