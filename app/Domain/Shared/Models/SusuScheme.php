<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use App\Domain\Account\Models\Account;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class SusuScheme
 *
 * @property string $id
 * @property string $resource_id
 * @property string $name
 * @property string|null $alias
 * @property string $type
 * @property string $code
 * @property string|null $description
 * @property string $status
 *
 * Relationships:
 * @property FeesAndCharge|null $feesAndCharges
 * @property Collection<int, Account> $accounts
 *
 * @method static Builder|SusuScheme whereResourceId($value)
 * @method static Builder|SusuScheme whereName($value)
 * @method static Builder|SusuScheme whereAlias($value)
 * @method static Builder|SusuScheme whereType($value)
 * @method static Builder|SusuScheme whereCode($value)
 * @method static Builder|SusuScheme whereDescription($value)
 * @method static Builder|SusuScheme whereStatus($value)
 *
 * @mixin Eloquent
 */
final class SusuScheme extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
        'name',
        'alias',
        'type',
        'code',
        'description',
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
     * @return HasOne
     */
    public function feesAndCharges(
    ): HasOne {
        return $this->hasOne(
            related: FeesAndCharge::class,
            foreignKey: 'susu_scheme_id'
        );
    }

    /**
     * @return HasMany
     */
    public function accounts(
    ): HasMany {
        return $this->hasMany(
            related: Account::class,
            foreignKey: 'account_id'
        );
    }
}
