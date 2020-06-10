# Icon

An icon picker field.

```php
$form->icon('icon')
    ->title('Icon')
    ->icons([
        '<i class="fas fa-address-book"></i>',
        '<i class="fas fa-address-card"></i>',
        // ...
    ])
    ->hint('Choose your icon.')
    ->width(2);
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

| Method          | Description                                                                 |
| --------------- | --------------------------------------------------------------------------- |
| `title`         | The title description for this field.                                       |
| `hint`          | A short hint that should describe how to use the field.`                    |
| `width`         | Width of the field.                                                         |
| `icons`         | List of selectable icons. (By default all fontawesome icons are selectable) |
| `rules`         | Rules that should be applied when **updating** and **creating**.            |
| `creationRules` | Rules that should be applied when **creating**.                             |
| `updateRules`   | Rules that should be applied when **updating**.                             |
