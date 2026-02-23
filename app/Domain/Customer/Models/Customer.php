<?php

declare(strict_types=1);

namespace App\Domain\Customer\Models;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCustomer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class Customer
 *
 * Represents an end user within the system who owns wallets and participates in one or more Accounts.
 *
 * The Customer model acts as the identity layer of the financial domain.
 * It stores minimal but sensitive identifying information and connects
 * customers to wallets and savings accounts through structured relationships.
 *
 * A customer may:
 * - Own multiple wallets.
 * - Participate in multiple accounts.
 * - Hold different roles across different accounts.
 *
 * Sensitive data such as the phone number is encrypted at rest to ensure data protection and regulatory compliance.
 *
 * Key Responsibilities:
 * - Maintains customer identity via a public `resource_id`.
 * - Securely stores encrypted contact information.
 * - Owns one or more wallets.
 * - Participates in accounts via the AccountCustomer relationship.
 *
 * Security & Privacy Notes:
 * - `phone_number` is encrypted using Laravel's encrypted casting.
 * - The internal numeric `id` is hidden from serialization.
 * - `resource_id` is used for routing and public-facing references.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property string $phone_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Collection|Wallet[] $wallets
 * @property-read Collection|AccountCustomer[] $accountCustomers
 * @property-read Collection|Account[] $accounts
 *
 * Domain Notes:
 * - This model represents the financial participant, not necessarily a full KYC profile (which may exist in a separate bounded context).
 * - Account participation logic should be managed through AccountCustomer to maintain proper role and wallet associations.
 */
final class Customer extends Model
{
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
    public function accountCustomers(
    ): HasMany {
        return $this->hasMany(
            related: AccountCustomer::class,
            foreignKey: 'customer_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function accounts(
    ): BelongsToMany {
        return $this->belongsToMany(
            related: Account::class,
            table: 'account_customers'
        )
            ->withPivot(['role', 'wallet_id'])
            ->withTimestamps();
    }
}
