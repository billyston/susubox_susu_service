<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\HasUuid;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class AccountCycleEntry
 *
 * Represents a single entry within an account cycle.
 * Each entry is typically created from a transaction or payment instruction
 * and records the unit count, monetary value, posting time, and status.
 *
 * @property int $id
 * @property string $resource_id
 *
 * @property int $account_cycle_id
 * @property int|null $transaction_id
 * @property int|null $payment_instruction_id
 *
 * @property int $units
 * @property mixed $amount
 * @property string $entry_type
 *
 * @property Carbon|null $posted_at
 * @property string $status
 *
 * @property-read AccountCycle $cycle
 * @property-read Transaction|null $transaction
 * @property-read PaymentInstruction|null $payment
 */
final class AccountCycleEntry extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => MoneyCasts::class,
        'posted_at' => 'datetime',
    ];

    protected $fillable = [
        'resource_id',
        'account_cycle_id',
        'transaction_id',
        'payment_instruction_id',
        'amount',
        'currency',
        'frequencies',
        'entry_type',
        'posted_at',
        'status',
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
    public function cycle(
    ): BelongsTo {
        return $this->belongsTo(
            related: AccountCycle::class,
            foreignKey: 'account_cycle_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function transaction(
    ): BelongsTo {
        return $this->belongsTo(
            related: Transaction::class,
            foreignKey: 'transaction_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function payment(
    ): BelongsTo {
        return $this->belongsTo(
            related: PaymentInstruction::class,
            foreignKey: 'payment_instruction_id'
        );
    }
}
