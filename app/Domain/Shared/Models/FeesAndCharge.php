<?php

declare(strict_types=1);

namespace App\Domain\Shared\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class FeesAndCharge
 *
 * @property string $id
 * @property string $resource_id
 * @property string $susu_scheme_id
 * @property string $category
 * @property string $collection_cycle
 * @property string $settlement_cycle
 * @property float $commission
 * @property float $charge
 * @property float $fee
 *
 * Relationships:
 * @property SusuScheme $susuScheme
 *
 * @method static Builder|FeesAndCharge whereResourceId($value)
 * @method static Builder|FeesAndCharge whereSusuSchemeId($value)
 * @method static Builder|FeesAndCharge whereCategory($value)
 * @method static Builder|FeesAndCharge whereCollectionCycle($value)
 * @method static Builder|FeesAndCharge whereSettlementCycle($value)
 * @method static Builder|FeesAndCharge whereCommission($value)
 * @method static Builder|FeesAndCharge whereCharge($value)
 * @method static Builder|FeesAndCharge whereFee($value)
 *
 * @mixin Eloquent
 */
final class FeesAndCharge extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = [
        'resource_id',
        'susu_scheme_id',
        'category',
        'collection_cycle',
        'settlement_cycle',
        'commission',
        'charge',
        'fee',
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
    public function susuScheme(
    ): BelongsTo {
        return $this->belongsTo(
            related: SusuScheme::class,
            foreignKey: 'susu_scheme_id',
        );
    }
}
