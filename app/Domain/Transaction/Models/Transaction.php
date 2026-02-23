<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Models;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Transaction
 *
 * Represents a financial transaction in the SusuBox system, capturing all
 * details of money movement for accounts and wallets. This model handles
 * both credits and debits, associating them with accounts, wallets, and
 * payment instructions, and provides rich metadata for reporting and
 * auditing purposes.
 *
 * Purpose:
 * - Track money movement within the platform for accounts and wallets.
 * - Associate each transaction with a category, account, wallet, and
 *   optionally a payment instruction.
 * - Store financial amounts including base amount, charge, and total.
 * - Record transaction date, status, and additional descriptive metadata.
 * - Generate clear narrations for each transaction for audit and user
 *   communication purposes.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property int $transaction_category_id
 * @property int|null $payment_instruction_id
 * @property int|null $account_id
 * @property int|null $wallet_id
 * @property string $transaction_type
 * @property string $reference_number
 * @property float|int $amount
 * @property float|int $charge
 * @property float|int $total
 * @property string $currency
 * @property string|null $description
 * @property string|null $narration
 * @property Carbon $date
 * @property string|null $status_code
 * @property string|null $status
 * @property array|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * Relationships:
 * @property-read TransactionCategory $category
 * @property-read Account|null $account
 * @property-read Wallet|null $wallet
 * @property-read PaymentInstruction|null $paymentInstruction
 *
 * Methods:
 * - static narration(TransactionCategory|string $category, float $amount, string $account_number, string $wallet, string $date): string
 *   Generates a formatted narration describing the transaction, suitable for
 *   user statements and audit logs.
 * - getMetadata(): array
 *   Returns the extra metadata associated with the transaction.
 *
 * Domain Notes:
 * - All monetary values are cast using MoneyCasts for precision.
 * - Useful for reporting, reconciliations, and end-user account statements.
 */
final class Transaction extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => MoneyCasts::class,
        'total' => MoneyCasts::class,
        'charge' => MoneyCasts::class,
        'date' => 'datetime',
        'metadata' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'transaction_category_id',
        'payment_instruction_id',
        'account_id',
        'wallet_id',
        'transaction_type',
        'reference_number',
        'amount',
        'charge',
        'total',
        'currency',
        'description',
        'narration',
        'date',
        'status_code',
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
    public function category(
    ): BelongsTo {
        return $this->belongsTo(
            related: TransactionCategory::class,
            foreignKey: 'transaction_category_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function account(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function wallet(
    ): BelongsTo {
        return $this->belongsTo(
            related: Wallet::class,
            foreignKey: 'wallet_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function paymentInstruction(
    ): BelongsTo {
        return $this->belongsTo(
            related: PaymentInstruction::class,
            foreignKey: 'payment_instruction_id'
        );
    }

    /**
     * @param TransactionCategory|string $category
     * @param float $amount
     * @param string $account_number
     * @param string $wallet
     * @param string $date
     * @return string
     */
    public static function narration(
        TransactionCategory|string $category,
        float $amount,
        string $account_number,
        string $wallet,
        string $date,
    ): string {
        // Handle if a category object or code is passed
        if ($category instanceof TransactionCategory) {
            $action = $category->description ?? $category->name ?? 'A transaction';
        } else {
            $model = TransactionCategory::where('code', $category)->first();
            $action = $model?->description ?? $model?->name ?? 'A transaction';
        }

        // Determine direction text for clarity
        $direction = str_contains(strtolower($action), 'withdrawal')
            ? 'from'
            : 'into';

        // Return formatted narration
        return sprintf(
            '%s of GHS %s %s to susu account number %s from wallet: %s on %s',
            ucfirst($action),
            number_format($amount, 2),
            $direction,
            $account_number,
            $wallet,
            Carbon::parse($date)->format('F j, Y \a\t g:i A')
        );
    }

    /**
     * @return array
     */
    public function getMetadata(
    ): array {
        return $this->extra_data ?? [];
    }
}
