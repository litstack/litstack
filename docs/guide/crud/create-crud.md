# Create a CRUD-Model

## CRUD-Wizard

A CRUD-Model can be created with the following artisan command:

```shell
php artisan fjord:crud
```

A wizard will take you through all required steps for setting up da fresh CRUD-Model.

## Prepare Migration

Edit the newly created migration and add all table fields you need. Pay attention to translatable and non-translatable fields.

After all fields have been defined, the migration can be executed.

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

## Add to Navigation

Add a link to your main navigation by adding an array to the main navigation config at `resources/fjord/navigation/main.php`.

```php
[
    'title' => 'Articles',
    'link' => 'articles',
    'icon' =>'<i class="fas fa-newspaper"></i>'
],
```

The link should be your model's plural name, snake_cased. For the icon, you can chose any FontAwesome-icon.

## Edit CRUD-Config

Define the CRUD-Config in the created resource file: `resources/fjord/crud/{table_name}.php`. Learn how to set up a config file in the next chapter.
