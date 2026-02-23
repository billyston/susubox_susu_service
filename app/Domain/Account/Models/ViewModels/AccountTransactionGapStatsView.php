<?php

declare(strict_types=1);

namespace App\Domain\Account\Models\ViewModels;

use App\Domain\Account\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AccountTransactionGapStatsView extends Model
{
    protected $table = 'account_transaction_gap_stats_view';

    protected $guarded = ['id'];

    protected $casts = [
        'longest_transaction_gap_days' => 'integer',
        'longest_credit_gap_days' => 'integer',
        'longest_debit_gap_days' => 'integer',
    ];

    protected $fillable = [
        'resource_id',
    ];

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
}
