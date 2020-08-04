# Forms

[[toc]]

Forms provide a convenient way to store, organize and maintain data of many kinds, such as your page content. You may create as many `Forms` as you like.

Forms are divided into form `collections` to keep the overview. For example, the forms **home** and **faq**, which contain the page content for the pages **home** and **faq**, can be included in the `collection` **pages**.

## Create

A `form` can be created using the following artisan command:

```shell
php artisan fjord:form
```

A wizard will take you through all required steps. The corresponding `config` and the `controller` is created afterwards.

## Permissions

Now you need to specify a **permission group** in the `_make_form_permissions` migration. You can create a **permission group** for each form or only for each collection. For example, for the collection `pages` in which all forms for the static pages of a website are located, you can create a **group** for all pages or for each individual form.

The permissions `read {group}` and `update {group}` are created for all groups that are specified.

```php
protected $groups = [
    // For the collection:
    'pages',
    // Or for a single form:
    'page-home'
];
```

The migration can now simply be rolled back and re-run using the artisan command `fjord:form-permissions`.

```shell
php artisan fjord:nav-permissions
```

::: tip
Try to use as few groups as possible to keep permission **management** simple.
:::

## Controller (Authorization)

A controller has been created in `Controllers/Form/{collection}` in which the authorization for all operation is specified. Operations can be `read` and `update`.

```php
/**
 * Authorize request for permission operation and authenticated fjord-user.
 * Operations: read, update
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
    'icon' => fa('home'),
]),
```

## Configuration

Define the form config in the created config file: `Config/Form/{collection}/{form}Config.php`. First the controller must be specified in the config:

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

### Update Form

Next, the configuration for the [form](/docs/crud/config-form.html) can be adjusted.

## Retrieve Data

In order to retrieve the form data, you have to add the **Form Facade** to your controller. Data can now be easily retrieved with the `load` function like this:

```php
use Fjord\Support\Facades\Form;

$form = Form::load('pages', 'home');
```

This allows the data to be passed directly to a [View](https://laravel.com/docs/7.x/blade#displaying-data).

```php
use Fjord\Support\Facades\Form;

return view('home')->with([
    'home' => Form::load('pages', 'home')
]);
```

and be used in a Blade template:

```php
<h1>{{ $home->title }}</h1>
```

It is also possible to load all data for a collection as shown in the example:

```php
use Fjord\Support\Facades\Form;

$settings = Form::load('settings');

$settings->main->title;
```

::: tip

Use [View composers](https://laravel.com/docs/7.x/views#view-composers) to load global Form data to your `Views`.

```php
View::composer('*', function ($view) {
    $view->with('settings', Form::load('settings', 'main'));
});
```

:::
