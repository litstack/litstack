# Collections

Fjord-Collections provide a convenient way to store, organize und maintain data of many kinds, such as your page-content. You may create as many collections as you like.

In fact, Fjord comes with collection by default: **Pages** and **Settings**. Every page's content is defined by a single config in the `resources/fjord/pages` directory. Think of every page as a single collection.

## Creating a Collection

A new collection can simply be defined by adding a config file to a folder of your choice inside the `resources/fjord` directory, e.g. `collections`.

Let's say you want to provide a collection of sorted, featured Articles to your application.
Add a file `resources/fjord/collections/features.php` with a relation formfield:

```php
<?php
return [
    'form_fields' => [
        [
            'id' => 'articles', // this id will be referenced to when retrieving your collection data
            'type' => 'relation',
            'model' => \App\Models\Article::class,
            'edit' => 'articles',
            'many' => true,
            'preview' => [
                '{title}',
            ],
            'title' => 'Articles',
            'hint' => 'Choose articles that should be shown on the front Page.',
            'width' => 12,
            'button' => 'Add Article'
        ]
    ]
];
```

Next, you may add the collection to your navigation of choice:

```php
<?php

return [
    [
        'title' => 'Featured Articles',
        'link' => 'collections/featured', // name the link: directory/file
        'icon' =>'<i class="fas fa-newspaper"></i>'
    ],
];
```

You can now add Articles to your collection and sort them to your liking.

## Retrieve collection data

In order to retrieve the collection data, you have to add the **Form Facade** to your controller.
Data can now easily be retrieved:

```php
<?php

namespace App\Http\Controllers;

use AwStudio\Fjord\Support\Facades\Form;

class YourController extends Controller
{
    public function __invoke()
    {
        return view('featured')->with([
            'articles' => Form::load('collections', 'featured')->articles
        ]);
    }
}
```

and be used in a view:

```php
@foreach ($articles as $article)
    {{$article}}
@endforeach
```
