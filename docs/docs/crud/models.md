# Models

[[toc]]

The main task of an admin panel is to manage data. The package offers easy editing and managing of [Laravel Eloquent Models](https://laravel.com/docs/7.x/eloquent). For a clear administration of models, a suitable `index` table and the corresponding `create` and `update` form are needed. The package also comes with powerful open source packages to make models `translatable`, `sluggable` and to attach `media`. The following packages are used for this:

-   [Astronomic Translatable](https://docs.astrotomic.info/laravel-translatable/)
-   [Spatie Medialibrary](https://docs.spatie.be/laravel-medialibrary/v8/introduction/)
-   [Cviebrock Sluggable](https://github.com/cviebrock/eloquent-sluggable)

The `fjord/app/Config/Crud` directory contains all configuration files for your CRUD-Models. Each model has its own file and is set up individually. Configurations can be created for existing Models.

## Create

In order to create your index table and update & edit form three things are needed.

-   Model
-   Controller
-   Config

They can be created all at once using the following artisan command:

```shell
php artisan fjord:crud
```

A wizard will take you through all required steps for setting up a fresh CRUD-Model.

::: tip
If a Model already exists, it wont be changed. Only the configuration file and the controller will be created. This allows **existing** models to be made editable using `fjord:crud` as well.
:::

## Migrate

Edit the newly created migration and add all table fields you need. For the translation of models [laravel-translatable](https://docs.astrotomic.info/laravel-translatable/installation#migrations) from `astronomic` is used. Pay attention to translatable and non-translatable fields.

In the migration all **permissions** for the corresponding model are created in the `permissions` array. It's possible that the permissions are used by another model. For example, it makes sense not to give extra permissions for `article_states` but to use the permissions from `articles`. In this case the array can simply be left empty.

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
php artisan migrate
```

## Model(s)

Edit the created model in `app/models`. Add all fillable attributes to avoid [mass assignment](https://laravel.com/docs/5.8/eloquent#mass-assignment). If the Model is translatable you have to set the fillable attributes on the corresponding translation-model in the `App\Models\Translations` directory and add all translatable attributes like in the given example:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ...

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'text'];

    // ...
}
```

```php
<?php

namespace App\Models\Translations;

use Illuminate\Database\Eloquent\Model;

...

class ArticleTranslation extends Model
{
    use Sluggable;
    ...
}
```

::: warning
Make sure the **Sluggable** trait and configuration are in the correct Model when using **translatable** Models.
:::

## Controller (authorization)

A controller has been created in which the authorization for all operations is specified. Operations can be `create`, `read`, `update` and `delete`.

```php
public function authorize(FjordUser $user, string $operation): bool
{
    return $user->can("{$operation} articles");
}
```

In order to assign authorizations for individual models, the initial query can simply be edited in the controller:

```php
public function query()
{
    return $this->model::where('created_by', fjord_user()->id);
}
```

## Navigation

Add the navigation entry by adding the `crud.{table_name}` preset to your navigation.

```php
$nav->preset('crud.articles', [
    'icon' => fa('newspaper')
]),
```

## Configuration

Define the CRUD-Config in the created config file: `Config/Crud/{model}Config.php`. First the model and the controller must be specified in the config:

```php
use App\Models\Article;
use FjordApp\Controllers\Crud\ArticleController;

/**
 * Model class.
 *
 * @var string
 */
public $model = Article::class;

/**
 * Controller class.
 *
 * @var string
 */
public $controller = ArticleController::class;
```

### Index Table, Create & Update Form

Next, the configuration for the [Index Table](/docs/crud/config-index.html) and the **create** and **update** [Form](/docs/crud/config-form.html) can be adjusted.
