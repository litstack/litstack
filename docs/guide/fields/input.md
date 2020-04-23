# Input

A basic text input field.

## Examples

```php
$form->input('title')
    ->title('Title')
    ->placeholder('Title')
    ->hint('The Title is shown at the top of the page.')
    ->cols(6);

$form->input('length')
    ->title('Length')
    ->type('number')
    ->placeholder('The length in cm')
    ->hint('Enter the length in cm.')
    ->append('cm')
    ->cols(12);
```

## Methods

| Method         | Description                                                                                                                                                                                                                                    |
| -------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `title`        | The title description for this form field.                                                                                                                                                                                                     |
| `placeholder`  | The placeholder for this form field.                                                                                                                                                                                                           |
| `type`         | Sets an input type for the input. [Available types](https://bootstrap-vue.js.org/docs/components/form-input#input-type): **text**, **number**, **email**, **password**, **search**, **url**, **tel**, **date**, **time**, **range**, **color** |
| `hint`         | A short hint that should describe how to use the form field.                                                                                                                                                                                   |
| `cols`         | Cols of the field.                                                                                                                                                                                                                             |
| `translatable` | Should the form field be translatable? For translatable crud models, the translatable fields are automatically recognized.                                                                                                                     |
| `max`          | Max characters.                                                                                                                                                                                                                                |
| `prepend`      | Prepend the field.                                                                                                                                                                                                                             |
| `append`       | Append the field.                                                                                                                                                                                                                              |
