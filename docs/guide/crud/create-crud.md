# Models

The `fjord/app/Config/Crud` directory contains all configuration-files for your CRUD-Models. Each model has its own file and is set up individually. Configurations can be created for existing Models.

## Create

A CRUD-Model can be created using the following artisan command:

```shell
php artisan fjord:crud
```

A wizard will take you through all required steps for setting up da fresh CRUD-Model.

## Migrate

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

## Model(s)

Edit the created model in `app/models`. Add all fillable attributes to avoid [mass assignment](https://laravel.com/docs/5.8/eloquent#mass-assignment). If the Model is translatable you have to set the fillable attributes on the corresponding translation-model in the `App\Models\Translations` directory and add all translatable attributes like in the given example:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

...

class Article extends Model implements HasMediaContract, TranslatableContract
{
    use TrackEdits, HasMedia, Translatable;

    /**
     * Setup Model:
     *
     * Enter all fillable columns. Translated columns must also be set fillable.
     * Don't forget to also set them fillable in the coresponding translation-model!
     */
    ...
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

## Controller (authorization)

A controller has been created in which the authorization for all operation is specified. Operations can be `create`, `read`, `update`, `delete`.

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
    'title' => 'Articles',
    'icon' => '<i class="fas fa-newspaper">',
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

Next, the configuration for the [index table](/guide/crud/config-index.html) and the create and update [form](/guide/crud/config-form.html) can be adjusted.
