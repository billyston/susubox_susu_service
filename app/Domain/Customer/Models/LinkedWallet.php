<?php

declare(strict_types=1);

namespace App\Domain\Customer\Models;

use App\Domain\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class LinkedWallet extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'extra_data' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'customer_id',
        'wallet_name',
        'wallet_number',
        'network_code',
        'status',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function customer(
    ): BelongsTo {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id',
        );
    }

    public function transactions(
    ): HasMany {
        return $this->hasMany(
            related: Transaction::class,
            foreignKey: 'linked_wallet_id',
        );
    }
}
