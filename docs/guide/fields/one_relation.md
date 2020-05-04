# oneRelation

A relation picker for one relation. This relation can easily be appended to a model without a necessary database structure.

## Example

```php
use App\Models\Article;

$form->manyRelation('article')
    ->title('Article')
    ->model(Article::class)
    ->preview(function ($table) {
        $table->col('title');
    });
```

In the Model:

```php
public function articles()
{
    return $this->oneRelation('App/Models/Article', 'article');
}
```

## Methods

| Method         | Description                                                          |
| -------------- | -------------------------------------------------------------------- |
| `title`        | The title description for this field.                                |
| `hint`         | A short hint that should describe how to use the field.`             |
| `cols`         | Cols of the field.                                                   |
| `relationCols` | Cols of the selected relation.                                       |
| `model`        | The related Model class.                                             |
| `preview`      | A closure to define the table preview of the corresponding relation. |
| `confirm`      | Modal pops when unlinkin the relation and asks to confirm.           |
