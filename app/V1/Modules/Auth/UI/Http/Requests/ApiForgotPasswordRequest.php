<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Requests;

use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Http\FormRequest;

class ApiForgotPasswordRequest extends FormRequest
{
    public function authorize(AuthManager $authManager): bool
    {
        return $authManager->guard()->user() === null;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        /** @var array<string, list<string>> $rules */
        $rules = [
            'email' => [
                'string',
                'required',
                'email:rfc,dns',
            ],
        ];

        return $rules;
    }
}
