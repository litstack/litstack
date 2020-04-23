# Navigation

Your Fjord-app has two navigations: The topbar, and your Main-Navigation. The top navigation is for managing the admin interface, such as language, users and permissions. The Main-Navigation is intended for the main administration of the data of your application or your site.

Both navigations are configured in `fjord/app/Config/NavigationConfig.php` which looks as follows:

```php
class NavigationConfig extends Config
{
    protected function topbar(Navigation $nav)
    {
        // Build your topbar navigation in here.
    }

    protected function main(Navigation $nav)
    {
        // Build your main navigation in here.
    }
}
```

## Structure

The navigation structure is defined in an array. Simple entries, group titles and nested entries can be created. All entries are grouped in sections like so:

```php
$nav->section([

    // The Section title describes the following set of navigation entries.
    $nav->title('Bookings'),

    ...
]);
```

### Entry

Simple entries have a `title` and a `link`, the Main-Navigation can have an additional `icon`.

```php
$nav->entry('Home', [
    'link' => route('your.route'), // Route
    'icon' => '<i class="fas fa-home"></i>' // Fontawesome Icon
])
```

### Groups

Groups create nested navigation entries. However, only one level is allowed. This default is there to keep the menus clear.

```php
$nav->group([
    'title' => 'Pages',
    'icon' => '<i class="fas fa-file"></i>',
], [

    $nav->entry(...),
    ...

])
```

## Authorization

To hide navigation entries from users without the necessary permission, an `authorize` closure can be specified in which permissions for the logged in fjord user can be queried.

```php
use Fjord\User\Models\FjordUser;

$nav->entry('Home', [
    ...
    'authorize' => function(FjordUser $user) {
        return $user->can('read page-home');
    }
])

```

## Presets

To build navigation entries for e.g. Crud models you can use navigation presets in which the corresponding `route` and `authorization` has already been defined. This is useful in most cases, especially to keep the correct `authorization`. To edit the entry further you can specify an array with the navigation entry elements as second parameter.

```php
$nav->preset('crud.departments', [
    'title' => ucfirst(__f("models.departments")),
    'icon' => '<i class="fas fa-building">',
]),
```

A list of all registered navigation presets can be displayed with `php artisan fjord:nav`.
