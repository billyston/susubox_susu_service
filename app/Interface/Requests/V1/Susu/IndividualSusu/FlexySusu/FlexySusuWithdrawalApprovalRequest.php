<?php

declare(strict_types=1);

namespace App\Interface\Requests\V1\Susu\IndividualSusu\FlexySusu;

use Illuminate\Foundation\Http\FormRequest;

final class FlexySusuWithdrawalApprovalRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(
    ): bool {
        return true;
    }

    /**
     * @return array[]
     */
    public function rules(
    ): array {
        return [
            'data' => [
                'required',
            ],

            'data.type' => [
                'required',
                'string',
                'in:Pin',
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(
    ): array {
        return [
            'data.required' => 'The data field is invalid',

            'data.type.required' => 'The type is required',
            'data.type.string' => 'The type must be of a string',
            'data.type.in' => 'The type is invalid',
        ];
    }
}
