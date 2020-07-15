# Navigation

[[toc]]

Your admin app by default contains two navigation instances: the top bar and your main navigation. The top navigation is for managing the admin interface, such as language, users and permissions. The main navigation is intended for the administration of data in your application.

Both navigation instances are configured in `fjord/app/Config/NavigationConfig.php` which looks as follows:

```php
class NavigationConfig extends Config
{
    public function topbar(Navigation $nav)
    {
        // Build your topbar navigation in here.
    }

    public function main(Navigation $nav)
    {
        // Build your main navigation in here.
    }
}
```

![navigation](./screens/navigation.jpg 'navigation')

## Structure

The navigation structure is defined in an array. Simple entries, group titles and nested entries can be created. All entries are grouped within sections like this:

```php
$nav->section([

    // The Section title describes the following set of navigation entries.
    $nav->title('Bookings'),

    ...
]);
```

### Entry

Simple entries have a `title`, a `link`, and an `icon`. The [Font Awesome](https://fontawesome.com/icons?d=gallery&m=free) icons are included by default.

```php
$nav->entry('Home', [
    'link' => route('your.route'), // Route
    'icon' => '<i class="fas fa-home"></i>' // Font Awesome icon
])
```

### Groups

Create nested navigation entries in groups.

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

To hide navigation entries from users without the necessary permission, an `authorize` closure can be specified in which permissions for the logged in admin user can be queried.

```php
use Fjord\User\Models\FjordUser;

$nav->entry('Home', [
    // ...
    'authorize' => function(FjordUser $user) {
        return $user->can('read page-home');
    }
])

```

## Presets

To build navigation entries, for example for Crud models, you can use navigation presets in which the corresponding `route` and `authorization` have already been defined. This is useful in most cases, especially to maintain the correct `authorization`. To edit the entry further you can specify an array with the navigation entry elements in a second parameter.

```php
$nav->preset('crud.departments', [
    'icon' => fa('building')
]),
```

::: tip
A list of all registered navigation presets can be displayed with `php artisan fjord:nav`.
:::
