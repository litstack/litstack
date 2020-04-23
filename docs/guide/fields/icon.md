# Icon

An icon picker field.

## Example

```php
$form->icon('icon')
    ->title('Icon')
    ->icons([
        '<i class="fas fa-address-book"></i>',
        '<i class="fas fa-address-card"></i>',
        ...
    ])
    ->hint('Choose your icon.')
    ->cols(2);
```

If the number of icons is high, it is recommended to `require` the icons from a file.

```php
->icon(require('.../icons.php'));
```

## Add Your Own Icons

To import your own icons you have to specify the corresponding `css` file in the config `fjord.php`.

```php
'assets' => [
    ...
    'css' => [
        'icons/icons.css', // Add your icon's css file.
        ...
    ],
],
```

## Methods

| Method        | Description                                                                 |
| ------------- | --------------------------------------------------------------------------- |
| `title`       | The title description for this field.                                       |
| `hint`        | A short hint that should describe how to use the field.`                    |
| `cols`        | Cols of the field.                                                          |
| `icons`       | List of selectable icons. (By default all fontawesome icons are selectable) |
| `placeholder` | The placeholder for this form field.                                        |
| `max`         | Max characters.                                                             |
