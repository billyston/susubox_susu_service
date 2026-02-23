<?php

declare(strict_types=1);

namespace App\Domain\PaymentInstruction\Models;

use App\Domain\Account\Models\Account;
use App\Domain\Account\Models\AccountCustomer;
use App\Domain\Customer\Models\Wallet;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\HasUuid;
use App\Domain\Transaction\Models\Transaction;
use App\Domain\Transaction\Models\TransactionCategory;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * Class PaymentInstruction
 *
 * Represents a financial instruction initiated by a customer or system
 * to perform a specific transaction such as recurring debit, direct debit,
 * withdrawal, or settlement.
 *
 * The PaymentInstruction model acts as the intent or plan for a monetary
 * operation. It records the amounts, charges, total payable, associated
 * wallet, account, and customer details, and tracks approval and execution
 * status. It is the bridge between customer action and the resulting
 * Transaction records.
 *
 * Key Responsibilities:
 * - Stores monetary amounts including initial, charge, and total.
 * - Tracks the associated account, customer, and wallet.
 * - Links to a transaction category for classification.
 * - Tracks approval status and timestamp for authorized instructions.
 * - Maintains execution status and other metadata.
 * - Supports direct connection to recurring deposits and transactions generated from the instruction.
 *
 * Financial & Operational Notes:
 * - `amount` represents the principal value of the instruction.
 * - `charge` represents any fees applied.
 * - `total` is the sum of amount and charge, representing the total to be processed.
 * - Approval and status fields must be validated before execution.
 *
 * Metadata:
 * - The `metadata` field stores additional optional data related to the instruction.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $transaction_category_id
 * @property int $account_id
 * @property int|null $account_customer_id
 * @property int|null $wallet_id
 * @property Money $amount
 * @property Money|null $initial_amount
 * @property Money $charge
 * @property Money $total
 * @property string $currency
 * @property string|null $internal_reference
 * @property string $transaction_type
 * @property bool $accepted_terms
 * @property string $approval_status
 * @property Carbon|null $approved_at
 * @property string $status
 * @property array|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read TransactionCategory $transactionCategory
 * @property-read Account $account
 * @property-read AccountCustomer|null $accountCustomer
 * @property-read Wallet|null $wallet
 * @property-read RecurringDeposit|null $recurringDeposit
 * @property-read Collection|Transaction[] $transactions
 *
 * Helper Methods:
 * - getMetadata(): Returns the metadata array or an empty array if none exists.
 *
 * Domain Notes:
 * - Acts as the declarative intent for a transaction.
 * - Multiple transactions may be generated from a single PaymentInstruction depending on system processing and recurring structures.
 */
final class PaymentInstruction extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'initial_amount' => MoneyCasts::class,
        'amount' => MoneyCasts::class,
        'charge' => MoneyCasts::class,
        'total' => MoneyCasts::class,
        'approved_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'transaction_category_id',
        'account_id',
        'account_customer_id',
        'wallet_id',
        'amount',
        'charge',
        'total',
        'currency',
        'internal_reference',
        'transaction_type',
        'accepted_terms',
        'approval_status',
        'approved_at',
        'status',
        'metadata',
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
    public function transactionCategory(
    ): BelongsTo {
        return $this->belongsTo(
            related: TransactionCategory::class,
            foreignKey: 'transaction_category_id',
        );
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
    public function accountCustomer(
    ): BelongsTo {
        return $this->belongsTo(
            related: AccountCustomer::class,
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
     * @return HasOne
     */
    public function recurringDeposit(
    ): HasOne {
        return $this->hasOne(
            related: RecurringDeposit::class,
            foreignKey: 'payment_instruction_id',
        );
    }

    /**
     * @return HasMany
     */
    public function transactions(
    ): HasMany {
        return $this->hasMany(
            related: Transaction::class,
            foreignKey: 'payment_instruction_id',
        );
    }

    /**
     * @return array
     */
    public function getMetadata(
    ): array {
        return $this->metadata ?? [];
    }
}
