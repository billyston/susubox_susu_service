<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\GroupSusu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class NkabomNhyiraSusuPayoutQueue extends Model
{
    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
    ];

    public function nkabomNhyiraSusu(
    ): BelongsTo {
        return $this->belongsTo(
            related: NkabomNhyiraSusu::class,
            foreignKey: 'nkabom_nhyira_susu_id'
        );
    }

    public function member(
    ): BelongsTo {
        return $this->belongsTo(
            related: NkabomNhyiraSusuMember::class,
            foreignKey: 'nkabom_nhyira_susu_member_id'
        );
    }
}
