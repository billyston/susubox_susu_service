<?php

declare(strict_types=1);

namespace App\Domain\Customer\Models;

use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Models\IndividualSusu\DriveToOwnSusu;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class Customer
 *
 * @property string $id
 * @property string $resource_id
 * @property string|null $phone_number
 * @property Carbon|null $deleted_at
 *
 * Relationships:
 * @property Collection<int, Wallet> $wallets
 * @property Collection<int, DailySusu> $dailySusu
 * @property Collection<int, BizSusu> $bizSusu
 * @property Collection<int, GoalGetterSusu> $goalGetterSusu
 * @property Collection<int, DriveToOwnSusu> $driveToOwn
 *
 * @method static Builder|Customer whereResourceId($value)
 * @method static Builder|Customer wherePhoneNumber($value)
 * @method static Builder|Customer onlyTrashed()
 * @method static Builder|Customer withTrashed()
 * @method static Builder|Customer withoutTrashed()
 *
 * @mixin Eloquent
 */
final class Customer extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['id'];

    protected $casts = [
        'phone_number' => 'encrypted:string',
    ];

    protected $fillable = [
        'resource_id',
        'phone_number',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function wallets(
    ): HasMany {
        return $this->hasMany(
            related: Wallet::class,
            foreignKey: 'customer_id'
        );
    }

    public function dailySusu(
    ): HasMany {
        return $this->hasMany(
            related: DailySusu::class,
            foreignKey: 'customer_id'
        );
    }

    public function bizSusu(
    ): HasMany {
        return $this->hasMany(
            related: BizSusu::class,
            foreignKey: 'customer_id'
        );
    }

    public function goalGetterSusu(
    ): HasMany {
        return $this->hasMany(
            related: GoalGetterSusu::class,
            foreignKey: 'customer_id'
        );
    }

    public function driveToOwn(
    ): HasMany {
        return $this->hasMany(
            related: DriveToOwnSusu::class,
            foreignKey: 'customer_id'
        );
    }
}
