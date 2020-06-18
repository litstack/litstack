<?php

namespace Fjord\Crud;

class CrudValidator
{
    /**
     * Validate update or create request.
     *
     * @param CrudUpdateRequest|CrudCreateRequest $request
     * @param BaseForm $form
     * @param string|null $type
     * @return void
     */
    public static function validate($request, $form, $type = null)
    {
        $rules = $form->getRules($request, $type);
        $attributeNames = self::getValidationAttributeNames($form);

        $request->validate($rules, __f('validation'), $attributeNames);
    }

    /**
     * Get validaton attribute names form field titles.
     *
     * @param BaseForm $form
     * @return array
     */
    protected static function getValidationAttributeNames($form)
    {
        $names = [];

        foreach ($form->getRegisteredFields() as $field) {

            $title = $field->getTitle();

            if (!$field->translatable) {
                $names[$field->local_key] = $title;
            } else {
                foreach (config('translatable.locales') as $locale) {
                    $names["{$locale}.{$field->local_key}"] = "{$title} ({$locale})";
                }
            }
        }

        return $names;
    }
}
