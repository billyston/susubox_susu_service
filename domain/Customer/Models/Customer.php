<?php

declare(strict_types=1);

namespace Domain\Customer\Models;

use Domain\Susu\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Customer extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['id'];

    protected $casts = [];

    protected $fillable = [
        'id',
        'resource_id',
        'phone_number',
    ];

    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    public function linkedWallets(
    ): HasMany {
        return $this->hasMany(
            related: LinkedWallet::class,
            foreignKey: 'customer_id'
        );
    }

    public function susu(
    ): HasMany {
        return $this->hasMany(
            related: Account::class,
            foreignKey: 'customer_id'
        );
    }
}
