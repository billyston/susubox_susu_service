<?php

declare(strict_types=1);

namespace App\Domain\Customer\Models;

use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
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
 * @property Collection<int, IndividualAccount> $individual
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
    public function wallets(
    ): HasMany {
        return $this->hasMany(
            related: Wallet::class,
            foreignKey: 'customer_id'
        );
    }

    /**
     * @return HasMany
     */
    public function individual(
    ): HasMany {
        return $this->hasMany(
            related: IndividualAccount::class,
            foreignKey: 'customer_id'
        );
    }
}
