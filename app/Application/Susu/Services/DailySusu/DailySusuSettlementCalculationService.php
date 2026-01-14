<?php

declare(strict_types=1);

namespace App\Application\Susu\Services\DailySusu;

use App\Application\Susu\DTOs\DailySusu\AccountSettlement\DailySusuAccountSettlementRequestDTO;
use App\Application\Susu\ValueObjects\DailySusu\DailySusuSettlementCalculationVO;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final class DailySusuSettlementCalculationService
{
    /**
     * @param Money $uniteCharge
     * @param iterable $accountCycles
     * @param DailySusuAccountSettlementRequestDTO $requestDTO
     * @return DailySusuSettlementCalculationVO
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public static function execute(
        Money $uniteCharge,
        iterable $accountCycles,
        DailySusuAccountSettlementRequestDTO $requestDTO
    ): DailySusuSettlementCalculationVO {
        // Set the money variables
        $principal = Money::of(0, 'GHS');
        $totalCharges = Money::of(0, 'GHS');

        $cycleResourceIDs = [];

        // Loop through the $accountCycles and calculate the $principal and the $charges
        foreach ($accountCycles as $accountCycle) {
            $cycleResourceIDs[] = $accountCycle->resource_id;

            $principal = $principal->plus($accountCycle->contributed_amount);
            $totalCharges = $totalCharges->plus($uniteCharge);
        }

        // Return the DailySusuSettlementCalculationVO
        return DailySusuSettlementCalculationVO::create(
            principal: $principal,
            charges: $totalCharges,
            cycleResourceIDs: $cycleResourceIDs,
            settlementScope: $requestDTO->scope->value
        );
    }
}
