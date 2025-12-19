<?php

declare(strict_types=1);

namespace App\Application\Shared\Helpers;

use App\Domain\Shared\Models\Duration;
use Carbon\Carbon;

final class Helpers
{
    /**
     * @param float $amount
     * @param string $frequency
     * @param string $duration
     * @return float
     */
    public static function calculateDebit(
        float $amount,
        string $frequency,
        string $duration
    ): float {
        $totalDays = self::getDaysInDuration($duration)->days;

        return match (strtolower($frequency)) {
            'weekly' => round($amount / floor($totalDays / 7), 2),
            'monthly' => round($amount / floor($totalDays / 30), 2),

            default => round($amount / $totalDays, 2),
        };
    }

    /**
     * @param string $date
     * @return string
     */
    public static function calculateDate(
        string $date
    ): string {
        $today = date(format: 'Y-m-d');

        return match (strtolower($date)) {
            'next-week' => date(format: 'Y-m-d', timestamp: strtotime(datetime: $today.' +1 week')),
            'two-weeks' => date(format: 'Y-m-d', timestamp: strtotime(datetime: $today.' +2 week')),
            'next-month' => date(format: 'Y-m-d', timestamp: strtotime(datetime: $today.' +1 month')),

            default => $today,
        };
    }

    /**
     * @return Carbon
     */
    public static function getEndCollectionDate(
    ): Carbon {
        $currentDate = Carbon::now();

        return $currentDate->addYears(value: 50);
    }

    /**
     * @param string $date
     * @return Duration
     */
    public static function getDaysInDuration(
        string $date
    ): Duration {
        return Duration::where('code', '=', $date)->first();
    }

    /**
     * @param Carbon $date
     * @param int $days
     * @return string
     */
    public static function getDateWithOffset(
        Carbon $date,
        int $days
    ): string {
        // Add the given number of days
        $newDate = $date->addDays($days);

        // Return the new date as a string
        return $newDate->toDateString();
    }
}
