<?php

namespace App\Domain\Shared\Enums;

enum SettlementScopes: string
{
    case SELECTED_COMPLETED = 'selected_completed';
    case ALL_COMPLETED = 'all_completed';
    case ALL_INCLUDING_RUNNING = 'all_including_running';

    /**
     * @return array
     */
    public static function allowed(
    ): array {
        return array_column(self::cases(), 'value');
    }

    /**
     * Does this scope require explicit cycle selection?
     *
     * @return bool
     */
    public function requiresCycleIds(
    ): bool {
        return $this === self::SELECTED_COMPLETED;
    }

    /**
     * Does this scope include the running cycle?
     *
     * @return bool
     */
    public function includesRunningCycle(
    ): bool {
        return $this === self::ALL_INCLUDING_RUNNING;
    }
}
