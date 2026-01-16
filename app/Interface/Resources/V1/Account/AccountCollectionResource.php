<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\Account;

use App\Domain\Susu\Models\IndividualSusu\BizSusu;
use App\Domain\Susu\Models\IndividualSusu\DailySusu;
use App\Domain\Susu\Models\IndividualSusu\FlexySusu;
use App\Domain\Susu\Models\IndividualSusu\GoalGetterSusu;
use App\Domain\Susu\Models\IndividualSusu\IndividualAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AccountCollectionResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(
        Request $request
    ): array {
        // Return the resource array
        return [
            // Resource type and id
            'type' => 'Account',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'account_name' => $this->resource->account_name,
                'account_number' => $this->resource->account_number,
                'account_activity_period' => Carbon::parse($this->resource->account_activity_period)->toDateTimeString(),
                'status' => $this->resource->status,
                'date_created' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            ],

            // Relationships
            'relationships' => [
                'susu' => $this->resolveSusuResource($this->resource->accountable),
                'susu_scheme' => [
                    'type' => 'SusuScheme',
                    'attributes' => [
                        'name' => $this->resource->accountable->scheme->name,
                        'alias' => $this->resource->accountable->scheme->alias,
                        'code' => $this->resource->accountable->scheme->code,
                    ],
                ],
            ],
        ];
    }

    /**
     * @param IndividualAccount $accountable
     * @return array|null
     */
    private function resolveSusuResource(
        IndividualAccount $accountable
    ): array|null {
        // Get the susu using the accessor method from IndividualAccount
        $susu = $accountable->susu();

        return match (true) {
            $susu instanceof DailySusu => [
                'resource_id' => $susu->resource_id,
                'susu_amount' => $susu->susu_amount->getAmount()->__toString(),
                'initial_deposit' => $susu->initial_deposit->getAmount()->__toString(),
                'frequency' => $susu->frequency->code,
                'recurring_debit_status' => $susu->recurring_debit_status,
                'rollover_enabled' => $susu->rollover_enabled,
                'settlement_status' => $susu->settlement_status,
            ],
            $susu instanceof BizSusu => [
                'resource_id' => $susu->resource_id,
                'susu_amount' => $susu->susu_amount->getAmount()->__toString(),
                'initial_deposit' => $susu->initial_deposit->getAmount()->__toString(),
                'frequency' => $susu->frequency->code,
                'recurring_debit_status' => $susu->recurring_debit_status,
                'rollover_enabled' => $susu->rollover_enabled,
                'withdrawal_status' => $susu->withdrawal_status,
            ],
            $susu instanceof GoalGetterSusu => [
                'resource_id' => $susu->resource_id,
                'target_amount' => $susu->target_amount->getAmount()->__toString(),
                'susu_amount' => $susu->susu_amount->getAmount()->__toString(),
                'initial_deposit' => $susu->initial_deposit->getAmount()->__toString(),
                'frequency' => $susu->frequency->code,
                'recurring_debit_status' => $susu->recurring_debit_status,
                'rollover_enabled' => $susu->rollover_enabled,
                'withdrawal_status' => $susu->withdrawal_status,
            ],
            $susu instanceof FlexySusu => [
                'resource_id' => $susu->resource_id,
                'initial_deposit' => $susu->initial_deposit->getAmount()->__toString(),
                'is_collateralized' => $susu->is_collateralized,
            ],

            default => null,
        };
    }
}
