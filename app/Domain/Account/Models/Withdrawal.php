<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Casts\MoneyCasts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Withdrawal extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => MoneyCasts::class,
        'charge' => MoneyCasts::class,
        'total' => MoneyCasts::class,
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'amount',
        'charge',
        'total',
        'currency',
        'accepted_terms',
        'status',
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
