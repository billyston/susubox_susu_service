<?php

declare(strict_types=1);

namespace App\Application\Susu\Services\IndividualSusu\DailySusu\Settlement;

use App\Application\Susu\DTOs\IndividualSusu\DailySusu\Settlement\DailySusuSettlementRequestDTO;
use App\Application\Susu\ValueObjects\IndividualSusu\DailySusu\Settlement\DailySusuSettlementCalculationVO;
use Brick\Money\Exception\MoneyMismatchException;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;

final class DailySusuSettlementCalculationService
{
    /**
     * @param Money $uniteCharge
     * @param iterable $accountCycles
     * @param DailySusuSettlementRequestDTO $requestDTO
     * @return DailySusuSettlementCalculationVO
     * @throws MoneyMismatchException
     * @throws UnknownCurrencyException
     */
    public static function execute(
        Money $uniteCharge,
        iterable $accountCycles,
        DailySusuSettlementRequestDTO $requestDTO
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
