# Upgrading

## From v2.2 to v2.3

In Fjord 2.3 some structural changes were made to the config files. The following steps explain what has to be done to upgrade Fjord.

### 1. Update Dependencies

Update `aw-studio/fjord-permission`:

```shell
composer update aw-studio/fjord-permissions
```

### 2.

Search `use HasCrudForm` in `./fjord/app/Config` and remove the trait from all config files.

### 3. Update Fjord

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

### 5. Fix crud index tables.

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
