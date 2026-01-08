<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Models\HasUuid;
use App\Domain\Transaction\Models\Transaction;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Random\RandomException;

/**
 * Class Account
 *
 * @property string $id
 * @property string $resource_id
 * @property string $accountable_type
 * @property string $accountable_id
 * @property string $account_name
 * @property string $account_number
 * @property Carbon|null $account_activity_period
 * @property bool $accepted_terms
 * @property string $status
 *
 * Relationships:
 * @property Model $accountable
 * @property Collection<int, Transaction> $transactions
 * @property AccountBalance $accountBalance
 *
 * @method static Builder|Account whereResourceId($value)
 * @method static Builder|Account whereAccountableType($value)
 * @method static Builder|Account whereAccountableId($value)
 * @method static Builder|Account whereAccountName($value)
 * @method static Builder|Account whereAccountNumber($value)
 * @method static Builder|Account whereAccountActivityPeriod($value)
 * @method static Builder|Account whereAcceptedTerms($value)
 * @method static Builder|Account whereStatus($value)
 *
 * @mixin Eloquent
 */
final class Account extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'account_activity_period' => 'datetime',
        'accepted_terms' => 'boolean',
    ];

    protected $fillable = [
        'resource_id',
        'accountable_type',
        'accountable_id',
        'account_name',
        'account_number',
        'account_activity_period',
        'accepted_terms',
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
     * @return MorphTo
     */
    public function accountable(
    ): MorphTo {
        return $this->morphTo();
    }

    /**
     * @return HasMany
     */
    public function transactions(
    ): HasMany {
        return $this->hasMany(
            related: Transaction::class,
            foreignKey: 'account_id'
        );
    }

    /**
     * @return HasOne
     */
    public function accountBalance(
    ): HasOne {
        return $this->hasOne(
            related: AccountBalance::class,
            foreignKey: 'account_id'
        );
    }

    /**
     * @return mixed|null
     */
    public function scheme(
    ): mixed {
        if (! $this->accountable) {
            return null;
        }

        return $this->accountable->scheme;
    }

    /**
     * @return bool
     */
    public function isFirstTransaction(
    ): bool {
        return ! $this->transactions()->exists();
    }

    /**
     * @return string
     * @throws RandomException
     */
    public static function generateAccountNumber(
    ): string {
        do {
            $number = 'ACC' . date('Ymd') . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('account_number', $number)->exists());

        return $number;
    }

    /**
     * @return void
     */
    protected static function booted(
    ): void {
        Account::deleting(function (
            Account $account
        ) {
            $account->accountBalance()?->delete();
            $account->accountable()?->delete();
        });
    }
}
