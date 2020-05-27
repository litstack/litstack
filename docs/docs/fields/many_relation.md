# manyRelation

A relation picker multiple relations. This relation can easily be appended to a model without a necessary database structure.

```php
use App\Models\Article;

$form->manyRelation('articles')
    ->title('Articles')
    ->model(Article::class);
```

In the Model:

```php
public function articles()
{
    return $this->manyRelation('App/Models/Article', 'articles');
}
```

## Methods

| Method          | Description                                                                   |
| --------------- | ----------------------------------------------------------------------------- |
| `title`         | The title description for this field.                                         |
| `hint`          | A short hint that should describe how to use the field.`                      |
| `width`         | Width of the field.                                                           |
| `model`         | The related Model class.                                                      |
| `preview`       | A closure to define the table preview of the corresponding relation.          |
| `query`         | Modify preview query with eager loads and accessors that should be displayed. |
| `filter`        | Initial query builder for the selectable relations.                           |
| `confirm`       | Modal pops when unlinkin the relation and asks to confirm.                    |
| `sortable`      | Is relation sortable.                                                         |
| `showTableHead` | Whether the table head should be shown. (default: `false`)                    |
