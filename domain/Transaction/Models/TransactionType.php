<?php

declare(strict_types=1);

namespace Domain\Transaction\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class TransactionType extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'meta' => 'array',
    ];

    protected $fillable = [
        'resource_id',
        'name',
        'description',
        'meta',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function transactions(
    ): HasMany {
        return $this->hasMany(
            related: Transaction::class,
            foreignKey: 'transaction_type_id',
        );
    }
}
