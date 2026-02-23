<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Models;

use App\Domain\Account\Models\AccountCycle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class SettlementCycle
 *
 * Represents the pivot/linking entity between a Settlement and an AccountCycle.
 *
 * The SettlementCycle model defines which account cycle(s) are included
 * in a particular settlement. It enables a many-to-one relationship where
 * a single Settlement may cover multiple AccountCycle records.
 *
 * This model plays a structural role in mapping completed account cycles
 * to their corresponding payout (settlement), ensuring traceability and
 * auditability of distributed funds.
 *
 * Key Responsibilities:
 * - Associates a Settlement with a specific AccountCycle.
 * - Supports multi-cycle settlements.
 * - Ensures payout traceability at the cycle level.
 *
 * Attributes:
 * @property int $id
 * @property int $settlement_id
 * @property int $account_cycle_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Settlement $settlement
 * @property-read AccountCycle $accountCycle
 *
 * Domain Notes:
 * - Acts as a relational bridge (join model) rather than containing business logic.
 * - Critical for financial reporting and reconciliation between account cycles and their respective settlements.
 */
final class SettlementCycle extends Model
{
    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'account_settlement_id',
        'account_cycle_id',
    ];

    /**
     * @return BelongsTo
     */
    public function settlement(
    ): BelongsTo {
        return $this->belongsTo(
            related: Settlement::class,
            foreignKey: 'settlement_id',
        );
    }

    /**
     * @return BelongsTo
     */
    public function accountCycle(
    ): BelongsTo {
        return $this->belongsTo(
            related: AccountCycle::class,
            foreignKey: 'account_cycle_id',
        );
    }
}
