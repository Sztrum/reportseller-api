<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRegisterUserRequest extends FormRequest
{
    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        /** @var array<string, list<string>> $rules */
        $rules = [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'string',
                'required',
                'email:rfc,dns',
                'unique:users'
            ],
        ];

        return $rules;
    }
}
