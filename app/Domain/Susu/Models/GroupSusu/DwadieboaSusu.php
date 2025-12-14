<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\GroupSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Models\Frequency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class DwadieboaSusu extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'extra' => 'array',
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

    public function frequency(
    ): BelongsTo {
        return $this->belongsTo(
            related: Frequency::class,
            foreignKey: 'frequency_id'
        );
    }
}
