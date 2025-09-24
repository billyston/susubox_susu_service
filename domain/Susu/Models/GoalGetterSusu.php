<?php

declare(strict_types=1);

namespace Domain\Susu\Models;

use Domain\Shared\Casts\MoneyCasts;
use Domain\Shared\Models\Duration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class GoalGetterSusu extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'extra_data' => 'array',
        'target_amount' => MoneyCasts::class,
        'initial_deposit' => MoneyCasts::class,
    ];

    protected $fillable = [
        'account_id',
        'duration_id',
        'target_amount',
        'initial_deposit',
        'currency',
        'rollover_enabled',
        'is_collateralized',
        'recurring_debit_status',
        'withdrawal_status',
        'extra_data',
    ];

    public function susu(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id',
        );
    }

    public function duration(
    ): BelongsTo {
        return $this->belongsTo(
            related: Duration::class,
            foreignKey: 'duration_id'
        );
    }
}
