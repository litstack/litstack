# Forms

The `Config/Forms` directory contains all configuration-files for your Forms. `Forms` are grouped into their collection folders.

## Create

A `form` can be created using the following artisan command:

```shell
php artisan fjord:form
```

A wizard will take you through all required steps. The corresponding `config` and the `controller` is created after.

## Permissions

Now you need to specify a `permission` `group` in the `_make_form_permissions` `migration`. You can create a `permission` `group` for each form or only for each collection. For example, for the collection `pages` in which all forms for the static pages of a website are located, you can create a `group` for all pages or for each individual form.

The permissions `read {group}` and `update {group}` are created for all groups that are specified.

```php
protected $groups = [
    // For the collection:
    'pages',
    // Or for a single form:
    'page-home'
];
```

The migration can now simply be rolled back and re-run using the artisan command `fjord:nav-permissions`.

```shell
php artisan fjord:nav-permissions
```

## Controller (Authorization)

A controller has been created in `Controllers/Form/{collection}` in which the authorization for all operation is specified. Operations can be `read`, `update`.

```php
/**
 * Authorize request for permission operation and authenticated fjord-user.
 * Operations: create, read, update, delete
 *
 * @param FjordUser $user
 * @param string $operation
 * @return boolean
 */
public function authorize(FjordUser $user, string $operation): bool
{
    return $user->can("{$operation} pages");
}
```

## Navigation

Add the navigation entry by adding the `form.{collection}.{form}` preset to your navigation.

```php
$nav->preset('form.pages.home', [
    'title' => 'Home',
    'icon' => '<i class="fas fa-home">',
]),
```

## Configuration

Define the CRUD-Config in the created config file: `Config/Form/{collection}/{form}Config.php`. First the controller must be specified in the config:

```php
use FjordApp\Controllers\Form\Pages\HomeController;

/**
 * Controller class.
 *
 * @var string
 */
public $controller = HomeController::class;
```

### Container Size

By default, the containers for the update Form have a maximum width. If you want the containers to expand to the maximum width for a better overview, this can be achieved with `expandContainer`.

```php
/**
 * Set bootstrap container to fluid.
 *
 * @var boolean
 */
public $expandContainer = false;
```

## Update Form

Next, the configuration for the [form](/guide/crud/config-form.html) can be adjusted.

## Retrieve Data

In order to retrieve the Form data, you have to add the **Form Facade** to your controller.
Data can now easily be retrieved:

```php
<?php

use Fjord\Support\Facades\Form;

return view('featured')->with([
    'home' => Form::load('pages', 'home')
]);
```

and be used in a view:

```php
<h1>{{ $home->title }}</h1>
```

It is also possible to load all data for a collection as shown in the example:

```php
use Fjord\Support\Facades\Form;

$pages = Form::load('pages');

$pages->home->title;
```
