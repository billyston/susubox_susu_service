<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Models\Frequency;
use App\Domain\Shared\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class BizSusu extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'frequency_id',
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

    public function frequency(
    ): BelongsTo {
        return $this->belongsTo(
            related: Frequency::class,
            foreignKey: 'frequency_id'
        );
    }

    public function updateRecurringDebitStatus(
        string $status,
    ): void {
        $this->update([
            'recurring_debit_status' => $status,
        ]);
    }
}
