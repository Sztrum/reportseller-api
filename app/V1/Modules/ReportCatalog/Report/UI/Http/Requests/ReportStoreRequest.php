<?php

declare(strict_types=1);

namespace App\V1\Modules\ReportCatalog\Report\UI\Http\Requests;

use App\V1\Modules\ListingCatalog\Listing\Domain\Enums\ListingSellerTypeEnum;
use App\V1\Shared\UI\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

class ReportStoreRequest extends BaseRequest
{
    /**
     * @return array<string, array<int, string|\Illuminate\Contracts\Validation\ValidationRule|\Illuminate\Validation\Rules\In>>
     */
    public function rules(): array
    {
        return [
            'listing' => ['required', 'array'],

            'listing.url' => ['required', 'string', 'max:2048', 'url'],
            'listing.platform_slug' => [
                'required',
                'string',
                'max:50',
                Rule::exists('platforms', 'slug')->where(
                    static fn (Builder $query): Builder => $query->where('is_active', true)
                ),
            ],
            'listing.report_category_slug' => [
                'required',
                'string',
                'max:50',
                Rule::exists('report_categories', 'slug')->where(
                    static fn (Builder $query): Builder => $query->where('is_active', true)
                ),
            ],

            'listing.external_id' => ['nullable', 'string', 'max:191'],
            'listing.title' => ['nullable', 'string', 'max:191'],
            'listing.description' => ['nullable', 'string', 'max:5000'],
            'listing.price' => ['nullable', 'integer', 'min:0', 'max:999999999999'],
            'listing.currency' => ['nullable', 'string', 'size:3'],
            'listing.seller_type' => ['nullable', Rule::in(ListingSellerTypeEnum::values())],
            'listing.seller_name' => ['nullable', 'string', 'max:255'],
            'listing.location' => ['nullable', 'string', 'max:191'],
            'listing.thumbnail_url' => ['nullable', 'string', 'max:2048', 'url'],
            'listing.is_active_external' => ['nullable', 'boolean'],

            'report' => ['required', 'array'],
            'report.title' => ['required', 'string', 'min:5', 'max:255'],
            'report.description' => ['required', 'string', 'min:10', 'max:5000'],

            'author' => ['nullable', 'array'],
            'author.email' => ['nullable', 'string', 'email:rfc', 'max:255'],
            'author.consent_version' => ['nullable', 'string', 'max:50'],
            'author.consent_given_at' => ['nullable', 'date'],
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
