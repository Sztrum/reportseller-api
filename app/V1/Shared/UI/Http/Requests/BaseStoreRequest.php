<?php

declare(strict_types=1);

namespace App\V1\Shared\UI\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

abstract class BaseStoreRequest extends FormRequest
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
            $name = $this->normalizeArrayInput($this->input('name', []));

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
                foreach ($name as $lang => $nameValue) {
                    if (!empty($nameValue)) {
                        $nameFilled = true;

                        break;
                    }

                    if ($errorKey === '') {
                        $errorKey = 'name.' . (string) $lang;
                    }
                }
            }

            if (!$nameFilled) {
                $validator->errors()->add($errorKey, 'Pole "name" musi być wypełnione w co najmniej jednym języku.');
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
