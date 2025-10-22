<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\LinkedWallet;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\AccountWallet;
use App\Domain\Shared\Models\HasUuid;
use App\Domain\Shared\Models\SusuScheme;
use App\Domain\Susu\Models\BizSusu;
use App\Domain\Susu\Models\DailySusu;
use App\Domain\Susu\Models\FlexySusu;
use App\Domain\Susu\Models\GoalGetterSusu;
use App\Domain\Transaction\Models\Transaction;
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
        'initial_deposit' => MoneyCasts::class,
        'susu_amount' => MoneyCasts::class,
    ];

    protected $fillable = [
        'resource_id',
        'customer_id',
        'susu_scheme_id',
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
        do {
            $timestampPart = now()->format('ymd');
            $randomPart = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Merge both parts to get 12 digits — you can trim or pad if needed
            $uniqueDigits = substr("{$timestampPart}{$randomPart}", 0, 11);

            // Build full account number
            $accountNumber = sprintf('%s%s', $product_code, $uniqueDigits);
        } while (Account::where('account_number', $accountNumber)->exists());

        return $accountNumber;
    }
}
