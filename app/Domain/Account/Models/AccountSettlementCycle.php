<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AccountSettlementCycle
 *
 * Represents the linkage between an AccountSettlement and
 * the AccountCycles included in that settlement execution.
 *
 * This model acts as a join entity, allowing a single settlement
 * to aggregate multiple account cycles.
 *
 * @property int $id
 *
 * @property int $account_settlement_id
 * @property int $account_cycle_id
 *
 * @property-read AccountSettlement $settlement
 * @property-read AccountCycle $cycle
 */
final class AccountSettlementCycle extends Model
{
    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'account_settlement_id',
        'account_cycle_id',
    ];

    public function settlement(
    ): BelongsTo {
        return $this->belongsTo(
            related: AccountSettlement::class,
            foreignKey: 'account_settlement_id',
        );
    }

    public function cycle(
    ): BelongsTo {
        return $this->belongsTo(
            related: AccountCycle::class,
            foreignKey: 'account_cycle_id',
        );
    }
}
