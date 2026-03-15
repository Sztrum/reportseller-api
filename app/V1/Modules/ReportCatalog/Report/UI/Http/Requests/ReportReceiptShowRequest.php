<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\UI\Http\Requests;

use App\V1\Shared\UI\Http\Requests\BaseRequest;

class ReportReceiptShowRequest extends BaseRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'receipt_token' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        $messages = __('report::validation.custom');

        if (!is_array($messages)) {
            return [];
        }

        /** @var array<string, string> $messages */
        return $messages;
    }
}
