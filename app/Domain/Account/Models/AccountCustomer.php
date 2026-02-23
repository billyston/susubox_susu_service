<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\PaymentInstruction\Models\RecurringDeposit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class AccountCustomer
 *
 * Represents the membership of a Customer within an Account.
 *
 * The AccountCustomer model acts as the ownership and participation layer
 * between accounts and customers. It captures customer-specific metadata
 * such as wallet association, role/type within the account, lifecycle status,
 * and participation in recurring deposits and cycle-based contributions.
 *
 * This model is especially important in multi-customer accounts where:
 * - An account may have multiple customers.
 * - Each customer may have a distinct wallet.
 * - Each customer may initiate their own recurring deposits.
 * - Cycle entries are tracked per customer participation.
 *
 * Key Responsibilities:
 * - Defines the relationship between an account and a customer.
 * - Associates a specific wallet to the customer for this account context.
 * - Tracks when the customer joined the account.
 * - Manages customer-level recurring deposits.
 * - Tracks payment instructions initiated by the customer.
 * - Records participation in account cycle entries.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property int $account_id
 * @property int $customer_id
 * @property int $wallet_id
 * @property string $customer_type
 * @property Carbon|null $joined_at
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Account $account
 * @property-read Customer $customer
 * @property-read Wallet $wallet
 * @property-read Collection|RecurringDeposit[] $recurringDeposits
 * @property-read Collection|PaymentInstruction[] $paymentInstructions
 * @property-read Collection|AccountCycleEntry[] $accountCycleEntries
 *
 * Domain Notes:
 * - This model enables granular control over account participation.
 * - Financial instructions and cycle entries are scoped at the
 *   account-customer level rather than directly at the account level.
 */
final class AccountCustomer extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    protected $fillable = [
        'account_id',
        'customer_id',
        'wallet_id',
        'customer_type',
        'joined_at',
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
     * @return BelongsTo
     */
    public function account(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id',
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
    public function recurringDeposits(
    ): HasMany {
        return $this->hasMany(
            related: RecurringDeposit::class,
            foreignKey: 'account_customer_id',
        );
    }

    /**
     * @return BelongsTo
     */
    public function wallet(
    ): BelongsTo {
        return $this->belongsTo(
            related: Wallet::class,
            foreignKey: 'wallet_id',
        );
    }

    /**
     * @return HasMany
     */
    public function paymentInstructions(
    ): HasMany {
        return $this->hasMany(
            related: PaymentInstruction::class,
            foreignKey: 'account_customer_id',
        );
    }

    /**
     * @return HasMany
     */
    public function accountCycleEntries(
    ): HasMany {
        return $this->hasMany(
            related: AccountCycleEntry::class,
            foreignKey: 'account_customer_id',
        );
    }
}
