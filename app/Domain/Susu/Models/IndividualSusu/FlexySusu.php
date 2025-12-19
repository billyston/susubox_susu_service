<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\IndividualSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\HasUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * Class FlexySusu
 *
 * @property string $id
 * @property string $resource_id
 * @property string $individual_account_id
 * @property string|null $customer_id
 * @property string $wallet_id
 *
 * Monetary fields (casted via MoneyCasts):
 * @property mixed $initial_deposit
 *
 * @property string $currency
 * @property bool $is_collateralized
 * @property string $withdrawal_status
 *
 * Extra data:
 * @property array|null $extra_data
 *
 * Relationships:
 * @property IndividualAccount $individual
 * @property Customer|null $customer
 * @property Account|null $account
 * @property Wallet $wallet
 *
 * @method static Builder|FlexySusu whereResourceId($value)
 * @method static Builder|FlexySusu whereIndividualAccountId($value)
 * @method static Builder|FlexySusu whereWalletId($value)
 *
 * @mixin Eloquent
 */
final class FlexySusu extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'initial_deposit' => MoneyCasts::class,
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'individual_account_id',
        'wallet_id',
        'initial_deposit',
        'currency',
        'is_collateralized',
        'withdrawal_status',
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
     * @return BelongsTo
     */
    public function individual(
    ): BelongsTo {
        return $this->belongsTo(
            related: IndividualAccount::class,
            foreignKey: 'individual_account_id',
        );
    }

    /**
     * @return BelongsTo
     */
    public function customer(
    ): BelongsTo {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id',
        );
    }

    /**
     * @return HasOneThrough
     */
    public function account(
    ): HasOneThrough {
        return $this->hasOneThrough(
            related: Account::class,
            through: IndividualAccount::class,
            firstKey: 'id',
            secondKey: 'accountable_id',
            localKey: 'individual_account_id',
            secondLocalKey: 'id'
        )->where(
            column: 'accountable_type',
            operator: IndividualAccount::class,
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
}
