<?php

declare(strict_types=1);

namespace Domain\Shared\Models;

use Domain\Customer\Models\LinkedWallet;
use Domain\Susu\Models\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AccountWallet extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'account_id',
        'linked_wallet_id',
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

    public function wallet(
    ): BelongsTo {
        return $this->belongsTo(
            related: LinkedWallet::class,
            foreignKey: 'linked_wallet_id'
        );
    }
}
