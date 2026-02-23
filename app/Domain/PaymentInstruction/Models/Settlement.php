<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Models;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCycle;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\HasUuid;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * Class Settlement
 *
 * Represents the payout (disbursement) of funds to a customer or group member,
 * typically upon completion of an AccountCycle.
 *
 * The Settlement model encapsulates the financial execution of distributing
 * accumulated savings or cycle-based contributions to the entitled party.
 * It records the principal payout amount, associated charges, total disbursed
 * amount, and links the payout to the originating PaymentInstruction and Account.
 *
 * In cycle-based savings structures, settlements are commonly triggered by a customer
 * or automatically when a cycle is completed and a member becomes eligible for payout.
 *
 * Key Responsibilities:
 * - Records payout principal, charges, and total disbursed amount.
 * - Tracks the scope of settlement (e.g., single member, full cycle, group payout).
 * - Associates the settlement with an account and payment instruction.
 * - Tracks lifecycle status and completion timestamp.
 * - Links settlements to one or more AccountCycles through SettlementCycle.
 *
 * Financial Notes:
 * - `principal_amount` represents the gross payout amount before charges.
 * - `charge_amount` represents any fees deducted.
 * - `total_amount` represents the net amount processed for disbursement.
 * - All monetary values are strongly cast using MoneyCasts.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $account_id
 * @property int|null $payment_instruction_id
 * @property string|null $initiated_by
 * @property string $settlement_scope
 * @property Money $principal_amount
 * @property Money $charge_amount
 * @property Money $total_amount
 * @property string $currency
 * @property string $status
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Account $account
 * @property-read PaymentInstruction|null $paymentInstruction
 * @property-read Collection|SettlementCycle[] $settlementCycles
 * @property-read Collection|AccountCycle[] $accountCycles
 *
 * Domain Notes:
 * - Settlements are financial end-state operations and should be executed within database transactions to ensure balance integrity.
 * - A single settlement may span multiple account cycles depending on scope.
 */
final class Settlement extends Model
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
    public function paymentInstruction(
    ): BelongsTo {
        return $this->belongsTo(
            related: PaymentInstruction::class,
            foreignKey: 'payment_instruction_id',
        );
    }

    /**
     * @return HasMany
     */
    public function settlementCycles(
    ): HasMany {
        return $this->hasMany(
            related: SettlementCycle::class,
            foreignKey: 'settlement_id',
        );
    }

    public function accountCycles(
    ): HasManyThrough {
        return $this->hasManyThrough(
            related: AccountCycle::class,
            through: SettlementCycle::class,
            firstKey: 'settlement_id',
            secondKey: 'id',
            localKey: 'id',
            secondLocalKey: 'account_cycle_id',
        );
    }
}
