<?php

declare(strict_types=1);

namespace Domain\Susu\Models;

use Domain\Customer\Models\Customer;
use Domain\Customer\Models\LinkedWallet;
use Domain\Shared\Casts\MoneyCasts;
use Domain\Shared\Models\AccountWallet;
use Domain\Shared\Models\Frequency;
use Domain\Shared\Models\HasUuid;
use Domain\Shared\Models\SusuScheme;
use Domain\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Account extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'extra_data' => 'array',
        'initial_deposit' => MoneyCasts::class,
        'susu_amount' => MoneyCasts::class,
    ];

    protected $fillable = [
        'resource_id',
        'customer_id',
        'susu_scheme_id',
        'frequency_id',
        'account_name',
        'account_number',
        'purpose',
        'initial_deposit',
        'susu_amount',
        'currency',
        'start_date',
        'end_date',
        'account_activity_period',
        'accepted_terms',
        'extra_data',
        'status',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function customer(
    ): BelongsTo {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id'
        );
    }

    public function frequency(
    ): BelongsTo {
        return $this->belongsTo(
            related: Frequency::class,
            foreignKey: 'frequency_id'
        );
    }

    public function scheme(
    ): BelongsTo {
        return $this->belongsTo(
            related: SusuScheme::class,
            foreignKey: 'susu_scheme_id'
        );
    }

    public function wallets(
    ): BelongsToMany {
        return $this->belongsToMany(
            related: LinkedWallet::class,
            table: 'account_wallets',
            foreignPivotKey: 'account_id',
            relatedPivotKey: 'linked_wallet_id',
        );
    }

    public function daily(
    ): HasOne {
        return $this->hasOne(
            related: DailySusu::class,
            foreignKey: 'account_id'
        );
    }

    public function biz(
    ): HasOne {
        return $this->hasOne(
            related: BizSusu::class,
            foreignKey: 'account_id'
        );
    }

    public function goal(
    ): HasOne {
        return $this->hasOne(
            related: GoalGetterSusu::class,
            foreignKey: 'account_id'
        );
    }

    public function flexy(
    ): HasOne {
        return $this->hasOne(
            related: FlexySusu::class,
            foreignKey: 'account_id'
        );
    }

    public function transactions(
    ): HasManyThrough {
        return $this->hasManyThrough(
            related: Transaction::class,
            through: AccountWallet::class,
            firstKey: 'account_id',
            secondKey: 'account_id',
        );
    }

    public static function generateAccountNumber(
        string $product_code
    ): string {
        // Get the last auto-increment id
        $lastId = Account::max('id') ?? 0;

        // Next id
        $nextId = $lastId + 1;

        // Format into 12 digits (zero-padded)
        $sequence = sprintf('%012d', $nextId);

        // Return PRODUCTCODE-UNIQUEID
        return sprintf('%s-%s', $product_code, $sequence);
    }
}
