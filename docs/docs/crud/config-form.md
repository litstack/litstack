# Form Config

[[toc]]

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

All available fields can be found in the documentation under [Fields](/docs/fields/introduction.html).

## Col

With `col` fields can be grouped in a column. This is usefull to organize form elements of different heights side by side.

```php
$form->col(6, function($form) {
    // Build your form inside the col.
});
```

## Component

With `component` a custom **Vue component** can be integrated.

```php
$form->component('my-component');
```

Read the [Extend Vue](/docs/basics/vue.html#bootstrap-vue) section to learn how to register your own Vue components.

## Info

A good content-administration interface includes **descriptions** that help the user quickly understand what is happening on the interface. Such information can be created outside and inside of cards like so:

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

Now the page can be previewed for the devices **desktop**, **tablet** or **mobile** like in the following screenshot:

![Fjord Crud Preview](./preview.png 'Fjord Crud Preview')

### Default Device

The default device can be changed in the config `fjord.php` under 'crud.preview.default_device'.

```php
'crud' => [
    'preview' => [
        // Available devices: mobile, tablet, desktop
        'default_device' => 'desktop'
    ]
],
```
