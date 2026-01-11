<?php

namespace App\Domain\Shared\Enums;

enum FeeEvents: string
{
    case COLLECTION = 'collection';
    case CYCLE_END = 'cycle_end';
    case SETTLEMENT = 'settlement';
    case WITHDRAWAL = 'withdrawal';
    case EARLY_EXIT = 'early_exit';

    /**
     * @return array
     */
    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }
}
