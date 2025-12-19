<?php

declare(strict_types=1);

namespace App\Domain\Susu\Models\GroupSusu;

use App\Domain\Customer\Models\Customer;
use App\Domain\Customer\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class NkabomNhyiraSusuMember extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'joined_at' => 'datetime',
        'activated_at' => 'datetime',
        'slots' => 'integer',
    ];

    protected $fillable = [
        'nkabom_nhyira_susu_id',
        'customer_id',
        'wallet_id',
        'role',
        'slots',
        'joined_at',
        'activated_at',
        'status',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    /**
     * @return BelongsTo
     */
    public function nkabomNhyiraSusu(
    ): BelongsTo {
        return $this->belongsTo(
            related: NkabomNhyiraSusu::class,
            foreignKey: 'nkabom_nhyira_susu_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function customer(
    ): BelongsTo {
        return $this->belongsTo(
            related: Customer::class,
            foreignKey: 'customer_id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function wallet(
    ): BelongsTo {
        return $this->belongsTo(
            related: Wallet::class,
            foreignKey: 'wallet_id'
        );
    }
}
