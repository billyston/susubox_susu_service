<?php

declare(strict_types=1);

namespace App\Domain\Customer\Models;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Models\IndividualSusu\DriveToOwnSusu;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Transaction\Models\Transaction;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Wallet
 *
 * @property string $id
 * @property string $resource_id
 * @property string $customer_id
 * @property string $wallet_name
 * @property string $wallet_number
 * @property string $network_code
 * @property string $status
 * @property array|null $extra_data
 *
 * Relationships:
 * @property Customer $customer
 * @property Collection<int, DailySusu> $dailySusu
 * @property Collection<int, BizSusu> $bizSusu
 * @property Collection<int, GoalGetterSusu> $goalGetterSusu
 * @property Collection<int, FlexySusu> $flexySusu
 * @property Collection<int, DriveToOwnSusu> $driveToOwnSusu
 * @property Collection<int, Transaction> $transactions
 * @property Collection<int, PaymentInstruction> $paymentInstruments
 *
 * @method static Builder|Wallet whereResourceId($value)
 * @method static Builder|Wallet whereCustomerId($value)
 * @method static Builder|Wallet whereWalletName($value)
 * @method static Builder|Wallet whereWalletNumber($value)
 * @method static Builder|Wallet whereNetworkCode($value)
 * @method static Builder|Wallet whereStatus($value)
 *
 * @mixin Eloquent
 */
final class Wallet extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['id'];

    protected $casts = [
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'customer_id',
        'wallet_name',
        'wallet_number',
        'network_code',
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
     * @return HasMany
     */
    public function dailySusu(
    ): HasMany {
        return $this->hasMany(
            related: DailySusu::class,
            foreignKey: 'wallet_id',
        );
    }

    /**
     * @return HasMany
     */
    public function bizSusu(
    ): HasMany {
        return $this->hasMany(
            related: BizSusu::class,
            foreignKey: 'wallet_id',
        );
    }

    /**
     * @return HasMany
     */
    public function goalGetterSusu(
    ): HasMany {
        return $this->hasMany(
            related: GoalGetterSusu::class,
            foreignKey: 'wallet_id',
        );
    }

    /**
     * @return HasMany
     */
    public function flexySusu(
    ): HasMany {
        return $this->hasMany(
            related: FlexySusu::class,
            foreignKey: 'wallet_id',
        );
    }

    public function driveToOwnSusu(
    ): HasMany {
        return $this->hasMany(
            related: DriveToOwnSusu::class,
            foreignKey: 'wallet_id',
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
     * @return HasMany
     */
    public function transactions(
    ): HasMany {
        return $this->hasMany(
            related: Transaction::class,
            foreignKey: 'wallet_id',
        );
    }

    /**
     * @return HasMany
     */
    public function paymentInstruments(
    ): HasMany {
        return $this->hasMany(
            related: PaymentInstruction::class,
            foreignKey: 'wallet_id',
        );
    }
}
