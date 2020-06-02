<?php

namespace Fjord\Crud\Controllers\Concerns;

use Illuminate\Support\Str;
use Fjord\Crud\Requests\CrudUpdateRequest;

trait ManagesCrudValidation
{
    /**
     * Validate update or create request.
     *
     * @param CrudUpdateRequest|CrudCreateRequest $request
     * @return void
     */
    protected function validateRequest($request)
    {
        $rules = $this->config->form->getRules($request);
        $attributeNames = $this->getValidationAttributeNames();

        $request->validate($rules, __f('validation'), $attributeNames);
    }

    /**
     * Get validaton attribute names form field titles.
     *
     * @return array
     */
    protected function getValidationAttributeNames()
    {
        $names = [];

        foreach ($this->fields() as $field) {
            if (!$field->translatable) {
                $names[$field->local_key] = $field->title;
            } else {
                foreach (config('translatable.locales') as $locale) {
                    $names["{$locale}.{$field->local_key}"] = "{$field->title} ({$locale})";
                }
            }
        }

        return $names;
    }
}
