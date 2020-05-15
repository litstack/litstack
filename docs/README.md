---
home: true
heroText: null
heroImage: /logo.svg
tagline: Powerful Admin Panel For Laravel
actionText: Get Started →
actionLink: /docs/getting-started/introduction.html

features:
    - title: Laravel
      details: Just integrate Fjord into existing projects and do a lot with little learning using your knowledge about Laravel standards.
    - title: Code Driven Configuration
      details: Easy to deploy. Configure your Admin panel in the code.
    - title: Infinitely Extendable
      details: Extend the backend howerer you like with custom Vue components and packages.

footer: MIT Licensed | Made with ❤️ in Kiel
---

![Fjord Interface](./Ford_Highlight.jpg 'Fjord Interface')

## Editable Models In No Time

```php
$form->image('profile_image')
    ->maxFiles(1)
    ->title('Profile Image')
    ->cols(4);

$form->col(8, function ($col) {
    $col->input('name')
        ->title('Name');

    $col->relation('department')
        ->title('Department');
});
```

![Fjord Interface](./example_form.png 'Fjord Interface')
