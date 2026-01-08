<?php

declare(strict_types=1);

namespace App\Domain\Account\Models;

use App\Domain\Shared\Casts\MoneyCasts;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class AccountCycleDefinition
 *
 * @property string $definable_type
 * @property int|string $definable_id
 *
 * @property int $cycle_length
 * @property int|null $commission_frequencies
 * @property int|null $settlement_frequencies
 *
 * @property int $expected_frequencies
 * @property mixed $expected_cycle_amount
 * @property mixed $expected_settlement_amount
 * @property string $currency
 *
 * @property mixed|null $commission_amount
 *
 * @property-read Model|Eloquent $definable
 */
final class AccountCycleDefinition extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'expected_cycle_amount' => MoneyCasts::class,
        'expected_settlement_amount' => MoneyCasts::class,
        'commission_amount' => MoneyCasts::class,
    ];

    protected $fillable = [
        'definable_type',
        'definable_id',

        'cycle_length',
        'commission_frequencies',
        'settlement_frequencies',

        'expected_frequencies',
        'expected_cycle_amount',
        'expected_settlement_amount',
        'currency',

        'commission_amount',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    /**
     * @return MorphTo
     */
    public function definable(
    ): MorphTo {
        return $this->morphTo();
    }
}
