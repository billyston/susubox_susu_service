<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Models;

use App\Domain\Account\Models\Account;
use App\Domain\Customer\Models\LinkedWallet;
use App\Domain\Shared\Casts\MoneyCasts;
use App\Domain\Shared\Models\HasUuid;
use App\Domain\Transaction\Enums\TransactionStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Transaction extends Model
{
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => MoneyCasts::class,
        'total' => MoneyCasts::class,
        'charge' => MoneyCasts::class,
        'status' => TransactionStatus::class,
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'transaction_category_id',
        'linked_wallet_id',
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

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function category(
    ): BelongsTo {
        return $this->belongsTo(
            related: TransactionCategory::class,
            foreignKey: 'transaction_category_id'
        );
    }

    public function account(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id'
        );
    }

    public function wallet(
    ): BelongsTo {
        return $this->belongsTo(
            related: LinkedWallet::class,
            foreignKey: 'linked_wallet_id'
        );
    }

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
            '%s of GHS%s %s to Susu Account number %s from linked wallet: %s on %s',
            ucfirst($action),
            number_format((float) $amount, 2),
            $direction,
            $account_number,
            $wallet,
            Carbon::parse($date)->format('F j, Y \a\t g:i A')
        );
    }
}
