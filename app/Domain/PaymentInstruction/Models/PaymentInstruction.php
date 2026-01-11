<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Models;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountSettlement;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\HasUuid;
use App\Domain\Transaction\Models\Transaction;
use App\Domain\Transaction\Models\TransactionCategory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class PaymentInstruction
 *
 * @property string $id
 * @property string $resource_id
 * @property string $for_type
 * @property string $for_id
 * @property string $initiated_by_type
 * @property string $initiated_by_id
 * @property string $transaction_category_id
 * @property string $account_id
 * @property string $wallet_id
 *
 * Monetary fields (casted via MoneyCasts):
 * @property mixed $initial_amount
 * @property mixed $amount
 * @property mixed $charge
 * @property mixed $total
 *
 * @property string $currency
 * @property string $internal_reference
 * @property string $transaction_type
 * @property string $accepted_terms
 * @property string $approval_status
 * @property string $approved_at
 * @property string $status
 *
 * Extra data:
 * @property array|null $extra_data
 *
 * Relationships:
 * @property Model $for
 * @property Model $initiator
 * @property Account|null $account
 * @property AccountSettlement|null $settlement
 * @property Wallet|null $wallet
 * @property TransactionCategory|null $transactionCategory
 * @property Collection<int, Transaction> $transactions
 *
 * @method static Builder|PaymentInstruction whereResourceId($value)
 * @method static Builder|PaymentInstruction whereTransactionCategoryId($value)
 * @method static Builder|PaymentInstruction whereAccountId($value)
 * @method static Builder|PaymentInstruction whereWalletId($value)
 * @method static Builder|PaymentInstruction whereStatus($value)
 *
 * @mixin Eloquent
 */
final class PaymentInstruction extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'initial_amount' => MoneyCasts::class,
        'amount' => MoneyCasts::class,
        'charge' => MoneyCasts::class,
        'total' => MoneyCasts::class,
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'for_type',
        'for_id',
        'initiated_by_type',
        'initiated_by_id',
        'transaction_category_id',
        'account_id',
        'wallet_id',
        'amount',
        'charge',
        'total',
        'currency',
        'internal_reference',
        'transaction_type',
        'accepted_terms',
        'approval_status',
        'approved_at',
        'status',
        'extra_data',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    /**
     * @return MorphTo
     */
    public function for(
    ): MorphTo {
        return $this->morphTo(name: 'for');
    }

    /**
     * @return MorphTo
     */
    public function initiator(
    ): MorphTo {
        return $this->morphTo(name: 'initiated_by');
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
     * @return HasMany
     */
    public function settlement(
    ): HasMany {
        return $this->hasMany(
            related: AccountSettlement::class,
            foreignKey: 'payment_instruction_id',
        );
    }

    /**
     * @return BelongsTo
     */
    public function wallet(
    ): BelongsTo {
        return $this->belongsTo(
            related: Wallet::class,
            foreignKey: 'wallet_id',
        );
    }

    /**
     * @return BelongsTo
     */
    public function transactionCategory(
    ): BelongsTo {
        return $this->belongsTo(
            related: TransactionCategory::class,
            foreignKey: 'transaction_category_id',
        );
    }

    /**
     * @return HasMany
     */
    public function transactions(
    ): HasMany {
        return $this->hasMany(
            related: Transaction::class,
            foreignKey: 'payment_instruction_id',
        );
    }

    /**
     * @return array
     */
    public function getMetadata(
    ): array {
        return $this->extra_data ?? [];
    }
}
