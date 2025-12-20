<?php

declare(strict_types=1);

namespace App\Domain\Susu\Services\GoalGetterSusu;

use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Exceptions\SystemFailureException;
use App\Domain\Shared\Exceptions\UnauthorisedAccessException;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use Illuminate\Support\Facades\Log;
use Throwable;

final class GoalGetterSusuShowService
{
    /**
     * @param Customer $customer
     * @param GoalGetterSusu $goalGetterSusu
     * @return GoalGetterSusu
     * @throws SystemFailureException
     * @throws UnauthorisedAccessException
     */
    public static function execute(
        Customer $customer,
        GoalGetterSusu $goalGetterSusu,
    ): GoalGetterSusu {
        try {
            return match (true) {
                $goalGetterSusu->individual->customer_id !== $customer->id => throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this susu account.'
                ),
                $goalGetterSusu->individual->susuScheme->code !== config('susubox.susu_schemes.biz_susu_code') => throw new UnauthorisedAccessException(
                    message: 'You are not authorized to access this susu account.'
                ),

                // Return the BizSusu resource
                default => $goalGetterSusu,
            };
        } catch (
            UnauthorisedAccessException $unauthorisedAccessException
        ) {
            throw $unauthorisedAccessException;
        } catch (
            Throwable $throwable
        ) {
            // Log the full exception with context
            Log::error('Exception in GoalGetterSusuShowService', [
                'customer' => $customer,
                'goal_getter_susu' => $goalGetterSusu,
                'exception' => [
                    'message' => $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ],
            ]);

            // Throw the SystemFailureException
            throw new SystemFailureException(
                message: 'There was a system failure while trying to fetch the goal getter susu account.',
            );
        }
    }
}
