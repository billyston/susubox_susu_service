<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Casts\MoneyCasts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AccountPerformanceView extends Model
{
    protected $table = 'account_performance_view';

    protected $guarded = ['id'];

    protected $casts = [
        'total_credit_amount' => MoneyCasts::class,
        'total_debit_amount' => MoneyCasts::class,
        'net_transaction_balance' => MoneyCasts::class,

        'longest_transaction_gap_days' => 'integer',
        'longest_credit_gap_days' => 'integer',
        'longest_debit_gap_days' => 'integer',

        'first_transaction_date' => 'datetime',
        'last_transaction_date' => 'datetime',
        'last_successful_transaction_date' => 'datetime',
        'last_failed_transaction_date' => 'datetime',
    ];

    protected $fillable = [
        'resource_id',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function account(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id',
        );
    }
}
