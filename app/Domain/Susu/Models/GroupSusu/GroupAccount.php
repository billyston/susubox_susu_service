<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\GroupSusu;

use App\Domain\Account\Models\Account;
use App\Domain\Shared\Models\SusuScheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class GroupAccount extends Model
{
    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
        'susu_scheme_id',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function susuScheme(
    ): BelongsTo {
        return $this->belongsTo(
            related: SusuScheme::class,
            foreignKey: 'susu_scheme_id'
        );
    }

    public function account(
    ): MorphOne {
        return $this->morphOne(
            related: Account::class,
            name: 'accountable'
        );
    }

    public function susuable(
    ): MorphTo {
        return $this->morphTo();
    }

    public function nkabomNhyiraSusu(
    ): ?NkabomNhyiraSusu {
        return $this->susuable_type === NkabomNhyiraSusu::class ? $this->susuable : null;
    }

    public function dwadieboaSusu(
    ): ?DwadieboaSusu {
        return $this->susuable_type === DwadieboaSusu::class ? $this->susuable : null;
    }

    public function corporativeSusu(
    ): ?CorporativeSusu {
        return $this->susuable_type === CorporativeSusu::class ? $this->susuable : null;
    }
}
