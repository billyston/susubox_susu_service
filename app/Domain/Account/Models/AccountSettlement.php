<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Enums\Statuses;
use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * Class AccountSettlement
 *
 * Represents a settlement execution for an account.
 * A settlement aggregates one or more account cycles and records
 * the principal, charges, and total amounts involved.
 *
 * @property int $id
 * @property string $resource_id
 *
 * @property int $account_id
 * @property int|null $payment_instruction_id
 *
 * @property string|null $initiated_by
 * @property string $settlement_scope
 *
 * @property mixed $principal_amount
 * @property mixed $charge_amount
 * @property mixed $total_amount
 *
 * @property string $currency
 * @property string $status
 *
 * @property Carbon|null $completed_at
 *
 * @property-read Account $account
 * @property-read PaymentInstruction $payment
 * @property-read Collection<int, AccountSettlementCycle> $settlements
 * @property-read Collection<int, AccountCycle> $cycles
 */
final class AccountSettlement extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'principal_amount' => MoneyCasts::class,
        'charge_amount' => MoneyCasts::class,
        'total_amount' => MoneyCasts::class,
        'completed_at' => 'datetime',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'payment_instruction_id',
        'initiated_by',
        'settlement_scope',
        'principal_amount',
        'charge_amount',
        'total_amount',
        'currency',
        'status',
        'completed_at',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    /**
     * @return BelongsTo
     */
    public function account(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id',
        );
    }

    /**
     * @return BelongsTo
     */
    public function payment(
    ): BelongsTo {
        return $this->belongsTo(
            related: PaymentInstruction::class,
            foreignKey: 'payment_instruction_id',
        );
    }

    /**
     * @return HasMany
     */
    public function settlements(
    ): HasMany {
        return $this->hasMany(
            related: AccountSettlementCycle::class,
            foreignKey: 'account_settlement_id',
        );
    }

    public function cycles(
    ): HasManyThrough {
        return $this->hasManyThrough(
            related: AccountCycle::class,
            through: AccountSettlementCycle::class,
            firstKey: 'account_settlement_id',
            secondKey: 'id',
            localKey: 'id',
            secondLocalKey: 'account_cycle_id',
        );
    }

    /**
     * @return bool
     */
    public function isCompleted(
    ): bool {
        return $this->status === Statuses::COMPLETED->value;
    }

    /**
     * @return bool
     */
    public function isFailed(
    ): bool {
        return $this->status === Statuses::FAILED->value;
    }
}
