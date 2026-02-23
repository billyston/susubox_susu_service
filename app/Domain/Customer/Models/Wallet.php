<?php

declare(strict_types=1);

namespace App\Domain\Customer\Models;

use App\Domain\Account\Models\AccountCustomer;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class Wallet
 *
 * Represents a financial wallet owned by a Customer.
 *
 * The Wallet model acts as the transactional instrument through which a
 * customer interacts with accounts, payment instructions, and transactions.
 * Each wallet is tied to a single customer but may be associated with
 * multiple accounts and used for multiple financial operations.
 *
 * Key Responsibilities:
 * - Stores wallet identifiers such as `wallet_name`, `wallet_number`, and `network_code`.
 * - Tracks wallet status (active, inactive, etc.).
 * - Links to the owning customer.
 * - Connects to AccountCustomer records to participate in accounts.
 * - Facilitates payment instructions and transactions originating from / into this wallet.
 *
 * Security & Privacy Notes:
 * - Internal `id` is hidden from serialization.
 * - `resource_id` serves as the public-facing identifier.
 * - Sensitive operations should reference the wallet via `resource_id` rather than the internal numeric `id`.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $customer_id
 * @property string $wallet_name
 * @property string $wallet_number
 * @property string|null $network_code
 * @property string $status
 * @property array|null $extra_data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read Customer $customer
 * @property-read Collection|AccountCustomer[] $accountCustomers
 * @property-read Collection|PaymentInstruction[] $paymentInstruments
 * @property-read Collection|Transaction[] $transactions
 *
 * Domain Notes:
 * - This model represents a customer-controlled source of funds.
 * - Transactions, payments, and account participation should be scoped through this model to ensure traceability and integrity.
 */
final class Wallet extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['id'];

    protected $casts = [
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'customer_id',
        'wallet_name',
        'wallet_number',
        'network_code',
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
    public function accountCustomers(
    ): HasMany {
        return $this->hasMany(
            related: AccountCustomer::class,
            foreignKey: 'customer_id'
        );
    }

    /**
     * @return HasMany
     */
    public function paymentInstruments(
    ): HasMany {
        return $this->hasMany(
            related: PaymentInstruction::class,
            foreignKey: 'wallet_id',
        );
    }

    /**
     * @return HasMany
     */
    public function transactions(
    ): HasMany {
        return $this->hasMany(
            related: Transaction::class,
            foreignKey: 'wallet_id',
        );
    }
}
