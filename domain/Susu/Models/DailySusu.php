<?php

declare(strict_types=1);

namespace Domain\Susu\Models;

use Domain\Shared\Casts\MoneyCasts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DailySusu extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'extra_data' => 'array',
        'initial_deposit' => MoneyCasts::class,
    ];

    protected $fillable = [
        'account_id',
        'initial_deposit',
        'currency',
        'savings_duration',
        'rollover_enabled',
        'is_collateralized',
        'auto_settlement',
        'recurring_debit_status',
        'settlement_status',
        'extra_data',
    ];

    public function susu(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id',
        );
    }
}
