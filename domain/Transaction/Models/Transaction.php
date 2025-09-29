<?php

declare(strict_types=1);

namespace Domain\Transaction\Models;

use Domain\Shared\Casts\MoneyCasts;
use Domain\Susu\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Transaction extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'extra_data' => 'array',
        'amount' => MoneyCasts::class,
        'charge' => MoneyCasts::class,
        'total' => MoneyCasts::class,
    ];

    protected $fillable = [
        'resource_id',
        'account_id',
        'type',
        'reference',
        'frequencies',
        'amount',
        'charge',
        'total',
        'currency',
        'wallet',
        'description',
        'narration',
        'extra_data',
        'date',
        'status',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function type(
    ): HasOne {
        return $this->hasOne(
            related: TransactionType::class,
            foreignKey: 'transaction_type_id'
        );
    }

    public function account(
    ): BelongsTo {
        return $this->belongsTo(
            related: Account::class,
            foreignKey: 'account_id'
        );
    }
}
