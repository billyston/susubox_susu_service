<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Models;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class TransactionCategory
 *
 * @property string $id
 * @property string $resource_id
 * @property string $name
 * @property string $alias
 * @property string $code
 *
 * Relationships:
 * @property Collection<int, PaymentInstruction> $paymentInstructions
 *
 * @method static Builder|TransactionCategory whereResourceId($value)
 * @method static Builder|TransactionCategory whereName($value)
 * @method static Builder|TransactionCategory whereAlias($value)
 * @method static Builder|TransactionCategory whereCode($value)
 *
 * @mixin Eloquent
 */
final class TransactionCategory extends Model
{
    public $timestamps = false;

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

    public function paymentInstructions(
    ): HasMany {
        return $this->hasMany(
            related: PaymentInstruction::class,
            foreignKey: 'transaction_category_id',
        );
    }
}
