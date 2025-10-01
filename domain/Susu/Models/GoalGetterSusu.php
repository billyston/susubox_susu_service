<?php

declare(strict_types=1);

namespace Domain\Susu\Models;

use Domain\Shared\Casts\MoneyCasts;
use Domain\Shared\Models\Duration;
use Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class GoalGetterSusu extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'extra_data' => 'array',
        'target_amount' => MoneyCasts::class,
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'duration_id',
        'target_amount',
        'currency',
        'rollover_enabled',
        'is_collateralized',
        'recurring_debit_status',
        'withdrawal_status',
        'extra_data',
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

    public function duration(
    ): BelongsTo {
        return $this->belongsTo(
            related: Duration::class,
            foreignKey: 'duration_id'
        );
    }
}
