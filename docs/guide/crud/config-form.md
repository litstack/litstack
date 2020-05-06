# Form Config

In the `form` function of a **Form** or a **CRUD** config all components and fields are configured for editing the data.

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
$form->input('first_name')->title('First Name');
```

All available fields can be found in the documentation under [Fields](/guide/fields/introduction.html).

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

## Preview

There is the possibility to get a `preview` of the stored data directly in the update form. The **route** for this can be easily specified using the method `previewRoute`. For a CRUD Model, the corresponding Model is also passed as a parameter.

```php
public function previewRoute($article)
{
    return route('article', $article->id);
}
```

Now the page can be previewed for the devices **desktop**, **tablet** or **mobile**. The default device can be changed in the config `fjord.php` under 'crud.preview.default_device'.

```php
'crud' => [
    'preview' => [
        // Available devices: mobile, tablet, desktop
        'default_device' => 'desktop'
    ]
],
```
