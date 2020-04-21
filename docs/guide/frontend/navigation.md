# Navigation

Your Fjord-app has two navigations: The topbar, and your Main-Navigation. The top navigation is for managing the admin interface, such as language, users and permissions. The Main-Navigation is intended for the main administration of the data of your application or your site.

The default path for the navigation configuration is `resources/fjord/navigation`.

## Structure

The navigation structure is defined in an array. Simple entries, group titles and nested entries can be created.

### Entry

Simple entries have a `title` and a `link`, the Main-Navigation can have an additional `icon`.

```php
[
    'title' => 'Home',  // Nav entry title
    'link' => route('your.route'), // Route
    'icon' => '<i class="fas fa-home"></i>' // Fontawesome Icon
],
```

### Group Title

Navigation entries can be divided into groups to keep track of many entries. The group title is given as string and the corresponding entries follow.

```php
'Sites', // Group title
[
    'title' => 'Home',  // Nav entry title
    ...
],
```

### Nested Entries

Nested indentations are indicated in `children` as in the following example:

```php
[
    'title' => 'Pages',
    'icon' => '<i class="fas fa-file"></i>',
    'children' => [
        [
            'title' => 'Home',
            ...
        ],
        [
            'title' => 'Imprint',
            ...
        ],
        ...
    ]
],
```

## Authorization

To hide navigation entries from users without the necessary permission, an `authorization` closure can be specified in which permissions for the logged in fjord user can be queried.

```php
use Fjord\User\Models\FjordUser;
...
[
    'title' => 'Home',
    ...
    'authorization' => function(FjordUser $user) {
        return $user->can('read page-home');
    }
]
```

## Presets

To build navigation entries for e.g. Crud models you can use navigation presets in which the corresponding `route` and `authorization` has already been defined. This is useful in most cases, especially to keep the correct `authorization`. The presets are output with `fjord()->navEntry('{key}')`. To edit the entry further you can specify an array with the navigation entry elements as second parameter.

```php
[
    'Crud',
    fjord()->navEntry('crud.articles', [
        'icon' => '<i class="fas fa-newspaper"></i>'
    ]),
    ...
]
```

A list of all registered navigation presets can be displayed with `php artisan fjord:nav`.
