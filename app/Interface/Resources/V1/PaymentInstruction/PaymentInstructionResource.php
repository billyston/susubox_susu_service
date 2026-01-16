<?php

declare(strict_types=1);

namespace App\Interface\Resources\V1\PaymentInstruction;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class PaymentInstructionResource extends JsonResource
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
            'type' => 'PaymentInstruction',

            // Resource exposed attributes
            'attributes' => [
                'resource_id' => $this->resource->resource_id,
                'amount' => $this->resource->amount->getAmount()->__toString(),
                'charges' => $this->resource->charge->getAmount()->__toString(),
                'total' => $this->resource->total->getAmount()->__toString(),
                'currency' => $this->resource->total->getCurrency(),
                'internal_reference' => $this->resource->internal_reference,
                'transaction_type' => $this->resource->transaction_type,
                'approval_status' => $this->resource->approval_status,
                'approved_at' => $this->resource->approved_at,
                'accepted_terms' => $this->resource->accepted_terms,
                'status' => $this->resource->status,
                'date_created' => Carbon::parse($this->resource->created_at)->toDateTimeString(),
            ],

//                'payment_instruction' => [
//                    'type' => 'PaymentInstruction',
//                    'attributes' => [
//                        'resource_id' => $payment->resource_id,
//                        'amount' => $payment->amount->getAmount()->__toString(),
//                        'charges' => $payment->charge->getAmount()->__toString(),
//                        'total' => $payment->total->getAmount()->__toString(),
//                        'currency' => $payment->total->getCurrency()->__toString(),
//                        'status' => $payment->status,
//
//                        // Flags and optional fields from extra_data
//                        'extra_data' => [
//                            'is_initial_deposit' => $this->when(
//                                data_get($payment->extra_data, 'is_initial_deposit') !== null,
//                                (bool) data_get($payment->extra_data, 'is_initial_deposit')),
//                            'initial_deposit_frequency' => $this->when(
//                                data_get($payment->extra_data, 'initial_deposit_frequency') !== null,
//                                data_get($payment->extra_data, 'initial_deposit_frequency')
//                            ),
//                            'initial_deposit' => $this->when(
//                                data_get($payment->extra_data, 'initial_deposit.amount') !== null,
//                                data_get($payment->extra_data, 'initial_deposit.amount')
//                            ),
//                            'recurring_amount' => $this->when(
//                                data_get($payment->extra_data, 'recurring_amount.amount') !== null,
//                                data_get($payment->extra_data, 'recurring_amount.amount')
//                            ),
//                            'start_date' => $this->when(
//                                data_get($payment->extra_data, 'start_date') !== null,
//                                data_get($payment->extra_data, 'start_date')
//                            ),
//                            'end_date' => $this->when(
//                                data_get($payment->extra_data, 'end_date') !== null,
//                                data_get($payment->extra_data, 'end_date')
//                            ),
//                            'frequency' => $this->when(
//                                data_get($payment->extra_data, 'frequency') !== null,
//                                data_get($payment->extra_data, 'frequency')
//                            ),
//                            'rollover_enabled' => $this->when(
//                                data_get($payment->extra_data, 'rollover_enabled') !== null,
//                                (bool) data_get($payment->extra_data, 'rollover_enabled')
//                            ),
//                        ],
//                    ]
//                ],
        ];
    }
}
