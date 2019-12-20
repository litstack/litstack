# Navigation

Your Fjord-app has two navigations: The topbar, which holds the roles/permission and the Settings-Collection by default, and your Main-Navigation.

## Main Navigation

You can add entries to the Navigation in the `resources/fjord/navigation/main.php`.

Links to pages (collections) should look like this:

```php
[
    'title' => 'Home',  // Link title
    'link' => 'pages/home', // home page set up in fjord/pages/home.php
    'icon' => '<i class="fas fa-home"></i>' // fontawesome icon
],
```

Links to CRUD-Models should look like this:

```php
[
    'title' => 'Posts',
    'link' => 'posts', // snake case plural name of your model
    'icon' => '<i class="fas fa-newspaper"></i>'// fontawesome icon
],
```

## Nesting Navigation-Entries

You can group navigation-entries by nesting them like this:

```php
[
    'title' => 'Pages',
    'icon' => '<i class="fas fa-file"></i>',
    'children' => [
        [
            'title' => 'Home',
            'link' => 'pages/home',
            'icon' => '<i class="fas fa-home"></i>'
        ],
        [
            'title' => 'Imprint',
            'link' => 'pages/imprint',
            'icon' => '<i class="fas fa-stamp"></i>'
        ],
        ...
    ]
],
```
