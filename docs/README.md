---
home: true
heroText: null
heroImage: /fjord-logo-padding.svg
tagline: Beautiful Content Administration
actionText: Get Started →
actionLink: /docs/getting-started/installation.html

features:
    - title: Laravel
      details: Just integrate Fjord into existing projects and do a lot with little learning using your knowledge about Laravel standards.
    - title: Code Driven Configuration
      details: Easy to deploy. Configure your Admin panel inside the code.
    - title: Infinitely Extendable
      details: Extend the backend however you like using Blade or Vue components and packages.

footer: MIT Licensed | Made with ❤️ in Kiel
---

![Fjord Interface](./fjord_preview.png 'Fjord Interface')

## Editable Models In No Time

```php
$form->image('profile_image')
    ->maxFiles(1)
    ->title('Profile Image')
    ->crop(3 / 4)
    ->width(4);

$form->group(function ($col) {
    $col->input('name')
        ->title('Name');

    $col->relation('department')
        ->title('Department');
})->width(2 / 3);
```

![Fjord Interface](./example_form.png 'Fjord Interface')
