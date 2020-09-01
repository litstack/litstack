<?php

namespace Ignite\Crud;

class CrudValidator
{
    public const UPDATE = 'update';

    public const CREATION = 'creation';

    /**
     * Validate update or create request.
     *
     * @param array       $attributes
     * @param BaseForm    $form
     * @param string|null $type
     *
     * @return void
     */
    public static function validate(array $attributes, BaseForm $form, $type = null)
    {
        $rules = $form->getRules($type);
        $attributeNames = self::getValidationAttributeNames($form);

        validator()->validate($attributes, $rules, __lit('validation'), $attributeNames);
    }

    /**
     * Get validaton attribute names form field titles.
     *
     * @param BaseForm $form
     *
     * @return array
     */
    protected static function getValidationAttributeNames($form)
    {
        $names = [];

        foreach ($form->getRegisteredFields() as $field) {
            $title = $field->getTitle();

            $localKey = str_replace('_', ' ', $field->local_key);

            if (! $field->translatable) {
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
