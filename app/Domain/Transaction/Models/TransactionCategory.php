<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Models;

use App\Domain\PaymentInstruction\Models\PaymentInstruction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class TransactionCategory
 *
 * Represents a category or type of financial transaction within the SusuBox system.
 * Each transaction category defines the nature of the transaction (e.g., credit, debit,
 * withdrawal, recurring debit) and allows grouping and filtering of transactions for
 * reporting, auditing, and processing purposes.
 *
 * Purpose:
 * - Categorize transactions for clarity, reporting, and accounting.
 * - Provide a central reference for transactions across accounts, wallets, and payment instructions.
 * - Enable descriptive labeling and coding of transactions for audit trails.
 *
 * Routing:
 * - Uses `resource_id` as the route key for public-facing identification.
 *
 * Attributes:
 * @property int $id
 * @property string $resource_id
 * @property string $name
 * @property string|null $alias
 * @property string|null $code
 *
 * Relationships:
 * @property-read Collection|PaymentInstruction[] $paymentInstructions
 *
 * Methods:
 * - getRouteKeyName(): string
 *   Returns 'resource_id' for route model binding.
 *
 * Domain Notes:
 * - Categories are central to the system for tracking and reporting all money movements.
 * - Can be referenced by PaymentInstruction and Transaction models.
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

    /**
     * @return string
     */
    public function getRouteKeyName(
    ): string {
        return 'resource_id';
    }

    /**
     * @return HasMany
     */
    public function paymentInstructions(
    ): HasMany {
        return $this->hasMany(
            related: PaymentInstruction::class,
            foreignKey: 'transaction_category_id',
        );
    }
}
