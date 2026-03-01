<?php

declare(strict_types=1);

namespace App\V1\Modules\Auth\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiVerifyEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        /** @var array<string, list<string>> $rules */
        $rules = [];

        return $rules;
    }
}
