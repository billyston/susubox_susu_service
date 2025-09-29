<?php

declare(strict_types=1);

namespace Domain\Susu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class FlexySusu extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'account_id',
        'currency',
        'rollover_enabled',
        'is_collateralized',
        'recurring_debit_status',
        'withdrawal_status',
        'extra_data',
    ];

    public function account(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id',
        );
    }
}
