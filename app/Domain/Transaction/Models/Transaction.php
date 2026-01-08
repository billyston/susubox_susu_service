<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Models;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\Wallet;
use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use App\Domain\Shared\Casts\MoneyCasts;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Transaction
 *
 * @property string $id
 * @property string $resource_id
 * @property string $account_id
 * @property string $payment_instruction_id
 * @property string $transaction_category_id
 * @property string $transaction_type
 * @property string $wallet_id
 *
 * Monetary fields (casted via MoneyCasts):
 * @property mixed $amount
 * @property mixed $charge
 * @property mixed $total
 *
 * @property string|null $reference_number
 * @property string|null $frequencies
 * @property string $currency
 * @property string $wallet
 * @property string|null $description
 * @property string|null $narration
 * @property string|Carbon $date
 * @property string|int $status_code
 * @property string $status
 * @property array $getMetadata
 *
 * Extra data:
 * @property array|null $extra_data
 *
 * Relationships:
 * @property TransactionCategory $category
 * @property Account $account
 * @property Wallet $walletRelation
 * @property PaymentInstruction $payment
 *
 * @method static Builder|Transaction whereResourceId($value)
 * @method static Builder|Transaction whereAccountId($value)
 * @method static Builder|Transaction wherePaymentInstructionId($value)
 * @method static Builder|Transaction whereTransactionCategoryId($value)
 * @method static Builder|Transaction whereWalletId($value)
 * @method static Builder|Transaction whereStatus($value)
 *
 * @mixin Eloquent
 */
final class Transaction extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => MoneyCasts::class,
        'total' => MoneyCasts::class,
        'charge' => MoneyCasts::class,
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'payment_instruction_id',
        'transaction_category_id',
        'transaction_type',
        'wallet_id',
        'reference_number',
        'frequencies',
        'amount',
        'charge',
        'total',
        'currency',
        'wallet',
        'description',
        'narration',
        'date',
        'status_code',
        'status',
        'extra_data',
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
    public function payment(
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
