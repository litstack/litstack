# manyRelation

A relation picker multiple relations. This relation can easily be appended to a model without a necessary database structure.

## Example

```php
use App\Models\Article;

$form->manyRelation('articles')
    ->title('Articles')
    ->model(Article::class)
    ->sortable()
    ->preview(function ($table) {
        $table->col('title');
    });
```

In the Model:

```php
public function articles()
{
    return $this->manyRelation('App/Models/Article', 'articles');
}
```

## Methods

| Method         | Description                                                                   |
| -------------- | ----------------------------------------------------------------------------- |
| `title`        | The title description for this field.                                         |
| `hint`         | A short hint that should describe how to use the field.`                      |
| `cols`         | Cols of the field.                                                            |
| `relationCols` | Cols of the selected relation.                                                |
| `model`        | The related Model class.                                                      |
| `preview`      | A closure to define the table preview of the corresponding relation.          |
| `previewQuery` | Modify preview query with eager loads and accessors that should be displayed. |
| `confirm`      | Modal pops when unlinkin the relation and asks to confirm.                    |
| `sortable`     | Is relation sortable.                                                         |
