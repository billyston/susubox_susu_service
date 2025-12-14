<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\GroupSusu;

use App\Domain\Shared\Models\Duration;
use App\Domain\Shared\Models\Frequency;
use Brick\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class NkabomNhyiraSusu extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'susu_amount' => Money::class,
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'group_account_id',
        'frequency_id',
        'cycle_duration_id',
        'susu_amount',
        'currency',
        'member_min_slot',
        'member_max_slot',
        'filled_slots',
        'extra_data',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function groupAccount(
    ): BelongsTo {
        return $this->belongsTo(
            related: GroupAccount::class,
            foreignKey: 'group_account_id'
        );
    }

    public function frequency(
    ): BelongsTo {
        return $this->belongsTo(
            related: Frequency::class,
            foreignKey: 'frequency_id'
        );
    }

    public function cycleDuration(
    ): BelongsTo {
        return $this->belongsTo(
            related: Duration::class,
            foreignKey: 'cycle_duration_id'
        );
    }

    public function members(
    ): HasMany {
        return $this->hasMany(
            related: NkabomNhyiraSusuMember::class,
            foreignKey: 'nkabom_nhyira_susu_id',
        );
    }
}
