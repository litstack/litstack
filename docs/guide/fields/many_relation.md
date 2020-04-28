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

| Method        | Description                                                          |
| ------------- | -------------------------------------------------------------------- |
| `title`       | The title description for this field.                                |
| `hint`        | A short hint that should describe how to use the field.`             |
| `cols`        | Cols of the field.                                                   |
| `model`       | The related Model class.                                             |
| `preview`     | A closure to define the table preview of the corresponding relation. |
| `confirm`     | Modal pops when unlinkin the relation and asks to confirm.           |
| `sortable`    | Sortable relation (only works for `many` relations).                 |
| `orderColumn` | Order column. Default: `order_column`                                |
