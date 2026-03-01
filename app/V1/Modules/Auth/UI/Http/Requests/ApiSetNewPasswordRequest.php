<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Requests;

use App\V1\Modules\Auth\UI\Http\Rules\IsValidPasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class ApiSetNewPasswordRequest extends FormRequest
{
    /**
     * @return array<string, list<string|IsValidPasswordRule>>
     */
    public function rules(): array
    {
        /** @var array<string, list<string|IsValidPasswordRule>> $rules */
        $rules = [
            'password' => [
                'required',
                'string',
                'confirmed',
                new IsValidPasswordRule(),
            ],
        ];

        return $rules;
    }
}
