<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class TransactionCategory extends Model
{
    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
        'name',
        'alias',
        'code',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function transactions(
    ): HasMany {
        return $this->hasMany(
            related: Transaction::class,
            foreignKey: 'transaction_category_id',
        );
    }
}
