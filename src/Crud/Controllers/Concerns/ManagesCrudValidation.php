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
        return $this->fields()->mapWithKeys(function ($field) {
            return [$field->local_key => $field->title];
        })->toArray();
    }
}
