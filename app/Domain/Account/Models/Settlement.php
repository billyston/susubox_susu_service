<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Casts\MoneyCasts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Settlement extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => MoneyCasts::class,
        'commission' => MoneyCasts::class,
        'total' => MoneyCasts::class,
    ];

    protected $fillable = [
        'resource_id',
        'cycles',
        'frequencies',
        'amount',
        'commission',
        'currency',
        'accepted_terms',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function account(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id'
        );
    }
}
