<?php

declare(strict_types=1);

namespace App\V1\Modules\ListingCatalog\Listing\UI\Http\Requests;

use App\V1\Shared\UI\Http\Requests\BaseRequest;

class ListingAutofillFromUrlRequest extends BaseRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'url' => ['required', 'string', 'max:2048'],
        ];
    }
}
