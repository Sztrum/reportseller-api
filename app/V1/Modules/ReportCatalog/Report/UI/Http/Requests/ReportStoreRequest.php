<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\UI\Http\Requests;

use App\V1\Shared\UI\Http\Requests\BaseRequest;

class ReportStoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'listing' => ['required', 'array'],

            'listing.url' => ['required', 'string', 'max:2048', 'url'],
            'listing.platform_slug' => ['required', 'string', 'max:50', 'exists:platforms,slug'],
            'listing.report_category_slug' => ['required', 'string', 'max:50', 'exists:report_categories,slug'],

            'listing.external_id' => ['nullable', 'string', 'max:191'],
            'listing.title' => ['nullable', 'string', 'max:191'],
            'listing.price' => ['nullable', 'integer', 'min:0', 'max:999999999999'],
            'listing.currency' => ['nullable', 'string', 'size:3'],
            'listing.seller_type' => ['nullable', 'in:private,business'],
            'listing.seller_name' => ['nullable', 'string', 'max:255'],
            'listing.location' => ['nullable', 'string', 'max:191'],
            'listing.thumbnail_url' => ['nullable', 'string', 'max:2048', 'url'],
            'listing.is_active_external' => ['nullable', 'boolean'],

            'report' => ['required', 'array'],
            'report.title' => ['required', 'string', 'min:5 ', 'max:255'],
            'report.description' => ['required', 'string', 'min:10', 'max:5000'],

            'author' => ['nullable', 'array'],
            'author.consent_version' => ['nullable', 'string', 'max:50'],
            'author.consent_given_at' => ['nullable', 'date'],

            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*.storage_key' => ['required_with:attachments', 'string', 'max:1024'],
            'attachments.*.storage_disk' => ['nullable', 'string', 'max:50'],
            'attachments.*.original_name' => ['nullable', 'string', 'max:255'],
            'attachments.*.mime_type' => ['nullable', 'string', 'max:100'],
            'attachments.*.size_bytes' => ['nullable', 'integer', 'min:1', 'max:50000000'],
        ];
    }

    public function messages(): array
    {
        return trans('report::validation.custom');
    }
}
