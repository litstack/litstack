# Create CRUD-Models

The `resources/fjord/crud` directory contains all configuration-files for your CRUD-Models. Each model has its own file and is set up individually.

## CRUD-Wizard

A CRUD-Model can be created using the following artisan command:

```shell
php artisan fjord:crud
```

A wizard will take you through all required steps for setting up da fresh CRUD-Model.

## Prepare Migration

Edit the newly created migration and add all table fields you need. For the translation of models [laravel-translatable](https://docs.astrotomic.info/laravel-translatable/installation#migrations) from `astronomic` is used. Pay attention to translatable and non-translatable fields.

In the migration the permissions for the corresponding model are created in the `permissions` array. It can happen that the permissions are used by another model. For example, it makes sense not to give extra permissions for `article_states` but to use the permissions from permission. In this case the array can simply be left empty.

```php
class CreateArticlesTable extends Migration
{
    ...

    /**
     * Permissions that should be created for this crud.
     *
     * @var array
     */
    protected $permissions = [
        'create articles',
        'read articles',
        'update articles',
        'delete articles',
    ];

    ...
}
```

After all fields and permissions have been defined, the migration can be executed.

```shell
php aritsan migrate
```

## Prepare Model(s)

Edit the created model in `app/models`. Add all fillable attributes to avoid [mass assignment](https://laravel.com/docs/5.8/eloquent#mass-assignment). If the Model is translatable you have to set the fillable attributes on the corresponding translation-model in the `App\Models\Translations` directory and add all translatable attributes like in the given example:

```php
<?php

namespace App\Models;

use Fjord\Fjord\Models\Model as FjordModel;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Article extends FjordModel implements TranslatableContract
{
  use HasMediaTrait, Translatable;

  // enter all fillable columns. translated columns must also
  // be set fillable. don't forget to also set them fillable in
  // the coresponding translation-model
  protected $fillable = ['title', 'text'];

  public $translatedAttributes = ['title', 'text', 'slug'];

  ...
}
```

```php
<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ArticleTranslation extends Model
{
    use Sluggable;

    public $timestamps = false;
    protected $fillable = ['title', 'text'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

}
```

## Prepare Controller (authorization)

A controller has been created in which the authorization is specified.

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
    return $user->can("{$operation} articles");
}
```

## Add to Navigation

Add the navigation entry by adding the `crud.{table_name}` preset to `resources/fjord/navigation/main.php`.

```php
[
    fjord()->navEntry('crud.articles', [
        'icon' => '<i class="fas fa-newspaper"></i>'
    ]),
]
```

## Edit CRUD-Config

Define the CRUD-Config in the created resource file: `resources/fjord/crud/{table_name}.php`. Learn how to set up a config file in the next chapter.
