# Field Validation

Fields that are not **validated** entail security risks, any values can be stored for all fillable attributes without validation. It is therefore highly recommended to append validation rules to any field that handles a fillable attribute. The [Laravel documentation](https://laravel.com/docs/7.x/validation#available-validation-rules) describes all existing rules that can be used for request validation.

## Attach Rules

The following example shows how the rules are attached to a field:

```php
$form->input('text')
    ->title('Text')
    ->rules('required', 'min:10', 'max:50');
```

The **error messages** are displayed directly below the field. The **title** is displayed as attribute.

![Field Validation](./screens/validation/validation_1.png 'Fjord field validation')

## Update Rules

All rules specified via the method `rules` apply to the creation and updating of a model. Rules that should only apply for updating a model are defined in `updateRules` like this:

```php
$form->input('text')
    ->title('Text')
    ->rules('min:10')
    ->updateRules('max:50');
```

## Creation Rules

Similarly, rules can be specified only for creating a model.

```php
$form->input('text')
    ->title('Text')
    ->rules('min:10', 'max:50')
    ->creationRules('required');
```
