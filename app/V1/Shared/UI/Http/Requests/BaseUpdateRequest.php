<?php

declare(strict_types=1);

namespace App\V1\Shared\UI\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

abstract class BaseUpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'fields.*.name' => ['nullable', 'string'],
        ] + $this->getContentSectionsRules();
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $fields = $this->normalizeArrayInput($this->input('fields', []));

            // Sprawdź, czy pole "name" jest wypełnione w co najmniej jednym języku
            $nameFilled = false;
            $errorKey = '';
            foreach ($fields as $lang => $fieldData) {
                if (is_array($fieldData) && !empty($fieldData['name'])) {
                    $nameFilled = true;

                    break;
                }

                if ($errorKey === '') {
                    $errorKey = 'fields.' . (string) $lang . '.name';
                }
            }

            if (!$nameFilled) {
                $validator->errors()->add($errorKey, __('core::validation.name_required_in_one_language'));
            }
        });
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function getContentSectionsRules(): array
    {
        return [
            'content_sections' => ['array'],
            'content_sections.*.name' => ['required', 'string'],
            'content_sections.*.type' => ['required', 'string'],
            'content_sections.*.items' => ['required', 'array'],
            'content_sections.*.items.*.name' => ['required', 'string'],
        ];
    }

    private function saveContentSectionsToSession(): void
    {
        //        dump($this->allFiles());
        //        dd($this->get('content_sections'));
        session()->flash('failedValidationContentSections', $this->get('content_sections'));
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->get('content_sections')) {
            $this->saveContentSectionsToSession();
        }

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    /**
     * @param mixed $value
     * @return array<int|string, mixed>
     */
    private function normalizeArrayInput(mixed $value): array
    {
        return is_array($value) ? $value : [];
    }
}
