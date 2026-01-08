<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\IndividualSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Customer;
use App\Domain\Shared\Models\HasUuid;
use App\Domain\Shared\Models\SusuScheme;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class IndividualAccount
 *
 * @property int $id
 * @property string $resource_id
 * @property int $customer_id
 * @property int $susu_scheme_id
 *
 * @property Customer $customer
 * @property SusuScheme $scheme
 * @property Account|null $account
 *
 * @property DailySusu|null $dailySusu
 * @property BizSusu|null $bizSusu
 * @property GoalGetterSusu|null $goalGetterSusu
 * @property FlexySusu|null $flexySusu
 * @property DriveToOwnSusu|null $driveToOwnSusu
 *
 * @property BizSusu|DailySusu|DriveToOwnSusu|FlexySusu|GoalGetterSusu|null $susu
 *
 * @method static Builder|IndividualAccount whereResourceId(string $resourceId)
 * @method static Builder|IndividualAccount whereCustomerId(int $customerId)
 * @method static Builder|IndividualAccount whereSusuSchemeId(int $susuSchemeId)
 *
 * @mixin Eloquent
 */
final class IndividualAccount extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
        'customer_id',
        'susu_scheme_id',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    /**
     * @return MorphOne
     */
    public function account(
    ): MorphOne {
        return $this->morphOne(
            related: Account::class,
            name: 'accountable'
        );
    }

    /**
     * @return BelongsTo
     */
    public function customer(
    ): BelongsTo {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function scheme(
    ): BelongsTo {
        return $this->belongsTo(
            related: SusuScheme::class,
            foreignKey: 'susu_scheme_id'
        );
    }

    /**
     * @return HasOne
     */
    public function dailySusu(
    ): HasOne {
        return $this->hasOne(
            related: DailySusu::class,
            foreignKey: 'individual_account_id'
        );
    }

    /**
     * @return HasOne
     */
    public function bizSusu(
    ): HasOne {
        return $this->hasOne(
            related: BizSusu::class,
            foreignKey: 'individual_account_id'
        );
    }

    /**
     * @return HasOne
     */
    public function goalGetterSusu(
    ): HasOne {
        return $this->hasOne(
            related: GoalGetterSusu::class,
            foreignKey: 'individual_account_id'
        );
    }

    /**
     * @return HasOne
     */
    public function flexySusu(
    ): HasOne {
        return $this->hasOne(
            related: FlexySusu::class,
            foreignKey: 'individual_account_id'
        );
    }

    /**
     * @return HasOne
     */
    public function driveToOwnSusu(
    ): HasOne {
        return $this->hasOne(
            related: DriveToOwnSusu::class,
            foreignKey: 'individual_account_id'
        );
    }

    /**
     * @return BizSusu|DailySusu|DriveToOwnSusu|FlexySusu|GoalGetterSusu|mixed|null
     */
    public function susu(
    ): mixed {
        return $this->dailySusu
            ?? $this->bizSusu
            ?? $this->goalGetterSusu
            ?? $this->flexySusu
            ?? $this->driveToOwnSusu;
    }

    /**
     * @return void
     */
    protected static function booted(
    ): void {
        IndividualAccount::deleting(function (IndividualAccount $individualAccount) {
            $individualAccount->goalGetterSusu?->delete();
            $individualAccount->dailySusu?->delete();
            $individualAccount->bizSusu?->delete();
            $individualAccount->flexySusu?->delete();
            $individualAccount->driveToOwnSusu?->delete();
        });
    }
}
