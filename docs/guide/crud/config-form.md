# Form Config

In the `form` function of a `Form` or a `CRUD` config all components and fields are configured for editing the data.

```php
use Fjord\Crud\CrudForm;

public function form(CrudForm $form)
{
    // Define your form here.
}
```

## Card

Form fields must be grouped into `cards`. You are free to create only one or any number of cards for a form.

```php
$form->card(function ($form) {

    // Build form inside card here.

})
->title('Card Title')
->cols(8);
```

## Fields

Fields to edit are defined with `$form->{field}(...)`, like shown in the example:

```php
$form->input('first_name')
    ->title('First Name');
```

All fields can be found in the documentation under [Fields](/guide/fields/introduction.html).

## Component

Like in the index table you can include your own `Vue` components.

```php
$form->component('my-component');
```

## Info

A good content-administration interface includes descriptions that help the user quickly understand what is happening on the interface. Such information can be created outside and inside maps like so:

```php
$form->info('Adress')
    ->cols(4)
    ->text('This address appears on your <a href="'.route('invoices').'">invoices</a>.')
    ->text(...);
```

## Line

Another help to create clear forms is to separate sections with lines. Fjord comes with a helper to easily integrate them.

```php
$form->line();
```
