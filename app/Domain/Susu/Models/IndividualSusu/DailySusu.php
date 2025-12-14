<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\IndividualSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\Frequency;
use App\Domain\Shared\Models\HasUuid;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * Class DailySusu
 *
 * @property string $id
 * @property string $resource_id
 * @property string $individual_account_id
 * @property string|null $customer_id
 * @property string $wallet_id
 * @property string $frequency_id
 *
 * Monetary fields (casted via MoneyCasts):
 * @property mixed $susu_amount
 * @property mixed $initial_deposit
 *
 * @property string $currency
 * @property string|Carbon $start_date
 * @property string|Carbon|null $end_date
 * @property bool $rollover_enabled
 * @property bool $is_collateralized
 * @property bool $auto_settlement
 * @property string $recurring_debit_status
 * @property string $settlement_status
 *
 * Extra data:
 * @property array|null $extra_data
 *
 * Relationships:
 * @property IndividualAccount $individual
 * @property Customer|null $customer
 * @property Account|null $account
 * @property Wallet $wallet
 * @property Frequency $frequency
 *
 * @method static Builder|DailySusu whereResourceId($value)
 * @method static Builder|DailySusu whereIndividualAccountId($value)
 * @method static Builder|DailySusu whereWalletId($value)
 * @method static Builder|DailySusu whereFrequencyId($value)
 *
 * @mixin Eloquent
 */
final class DailySusu extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'susu_amount' => MoneyCasts::class,
        'initial_deposit' => MoneyCasts::class,
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'individual_account_id',
        'wallet_id',
        'frequency_id',
        'susu_amount',
        'initial_deposit',
        'currency',
        'start_date',
        'end_date',
        'rollover_enabled',
        'is_collateralized',
        'auto_settlement',
        'recurring_debit_status',
        'settlement_status',
        'extra_data',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function individual(
    ): BelongsTo {
        return $this->belongsTo(
            related: IndividualAccount::class,
            foreignKey: 'individual_account_id',
        );
    }

    public function customer(
    ): BelongsTo {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id',
        );
    }

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

    public function wallet(
    ): BelongsTo {
        return $this->belongsTo(
            related: Wallet::class,
            foreignKey: 'wallet_id',
        );
    }

    public function frequency(
    ): BelongsTo {
        return $this->belongsTo(
            related: Frequency::class,
            foreignKey: 'frequency_id',
        );
    }
}
