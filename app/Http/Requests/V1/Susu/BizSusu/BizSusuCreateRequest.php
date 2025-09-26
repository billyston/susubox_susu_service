<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Susu\BizSusu;

use Illuminate\Foundation\Http\FormRequest;

final class BizSusuCreateRequest extends FormRequest
{
    public function authorize(
    ): bool {
        return true;
    }

    public function rules(
    ): array {
        return [
            'data' => [
                'required',
            ],

            'data.type' => [
                'required',
                'string',
                'in:BizSusu',
            ],
        ];
    }

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
