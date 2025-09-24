<?php

declare(strict_types=1);

namespace Domain\Susu\Models;

use Domain\Customer\Models\Customer;
use Domain\Shared\Casts\MoneyCasts;
use Domain\Shared\Models\AccountWallet;
use Domain\Shared\Models\Frequency;
use Domain\Shared\Models\SusuScheme;
use Domain\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Account extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'extra_data' => 'array',
        'amount' => MoneyCasts::class,
    ];

    protected $fillable = [
        'resource_id',
        'customer_id',
        'susu_scheme_id',
        'frequency_id',
        'account_name',
        'account_number',
        'purpose',
        'amount',
        'currency',
        'start_date',
        'end_date',
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
    ): HasMany {
        return $this->hasMany(
            related: AccountWallet::class,
            foreignKey: 'account_id'
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
            related: GoalGetterSusu::class,
            foreignKey: 'account_id'
        );
    }

    public function transactions(
    ): HasManyThrough {
        return $this->hasManyThrough(
            related: Transaction::class,
            through: AccountWallet::class,
        );
    }

    public static function generateAccountNumber(
    ): string {
        return 'SUSU' . now()->format('YmdHis') . mt_rand(100000000000, 999999999999);
    }
}
