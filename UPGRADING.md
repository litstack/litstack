# Upgrading

## From v2.2 to v2.3

Updating v2.2 to v2.3 is not that easy. Version 2.3 has some structural changes in the config files. These changes make cool features like charts and more possible in the future. The following steps explain what has to be done to upgrade the app.

### 1. Update Dependencies

Update `aw-studio/fjord-permission`:

```shell
composer update aw-studio/fjord-permissions
```

### 2.

The trait `HasCrudForm` is deprecated. However it is only used in form configs that are located in `./fjord/app/Config/Form`. Just search for `HasCrudForm` and remove the trait from all config files.

### 3. Update

Update `aw-studio/fjord`:

```shell
composer update aw-studio/fjord
```

### 4. Artisan: fjord:upgrade

The artisan `fjord:upgrade` command is making some changes for you.

```shell
php artisan fjord:upgrade
```

These are:

-   Replacing ProfileSettingConfig & UserIndexConfig with new modified versions.
-   Publishing ProileSettingsController & UserIndexController
-   Fixing namespaces in the config files.
-   Fixing navigation preset keys.

### 5. Update navigation presets

The navigation preset keys have changed. Navigation presets that have pointed to a crud were given as `crud.{table_name}`. Now it is the snake_case folder name separated with dots. So `./fjord/app/Config/Crud/PostConfig.php` is now `crud.post` or `./fjord/app/Config/Form/Pages/HomeConfig.php` is now `form.pages.home`.

You can use `php artisan fjord:nav` to get a list of all existing navigation presets.

### 6. Upadte crud index tables

Update your crud index table config.

The crud index table configuration changes from:

```php
use Fjord\Vue\Crud\CrudTable;

class ArticleConfig extends CrudConfig
{
    public $search = ['title'];
    public $sortByDefault = 'id.desc';
    public function sortBy(): array;
    public function filter(): array;
    public function indexQuery(Builder $query);

    /**
     * Setup crud index container.
     *
     * @param \Fjord\Vue\Crud\CrudTable $table
     * @return void
     */
    public function index(CrudTable $table)
    {
        $table->col('Title')
            ->value('{name}')
            ->sortBy('name');
    }
}
```

to:

```php
class ArticleConfig extends CrudConfig
{
    /**
     * Setup crud index container.
     *
     * @param \Fjord\Crud\CrudIndex $container
     * @return void
     */
    public function index(CrudIndex $container)
    {
        $container->table(function ($table) {
            // Build your table colums in here
            $table->col('Title')
                ->value('{name}')
                ->sortBy('name');
            })
            // Configure the table.
            ->sortByDefault('id.desc')
            ->search(['title'])
            ->filter([
                // ...
            ])
            ->query(function($query) {
                // Replaces the "indexQuery" method.
            });
    }
}
```
