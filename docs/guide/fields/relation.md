# Relation

A relation picker. Relation picker can be created for any relation of your model.

For the relation Field only the name of the relation must be specified, the type of the relation is automatically recognized and displayed accordingly.

The relations Field can only be used for **Crud Models** and not in **Forms** or **Blocks**. For Forms or Blocks the [oneRelation](/guide/fields/one_relation.html) or [manyRelation](/guide/fields/many_relation.html) field can be used.

## Example

```php
$form->relation('articles')
    ->title('Articles')
    // An optional query builder can be defined:
    ->query(Articles::where('created_by', fjord_user()->id))
    ->confirm() // User gets asked to confirm unlinking the relation.
    ->sortable()
    ->preview(function ($table) {
        // Build the preview table in here.
        $table->col('title'); // In this case we are showing the article title.
    });
```

In the Model:

```php
public function articles()
{
    return $this->hasMany('App/Models/Article');
}
```

## Methods

| Method        | Description                                                          |
| ------------- | -------------------------------------------------------------------- |
| `title`       | The title description for this field.                                |
| `hint`        | A short hint that should describe how to use the field.`             |
| `cols`        | Cols of the field.                                                   |
| `query`       | Initial query builder for the selectable relations. (optional)       |
| `preview`     | A closure to define the table preview of the corresponding relation. |
| `confirm`     | Modal pops when unlinkin the relation and asks to confirm.           |
| `sortable`    | Sortable relation (only works for `many` relations).                 |
| `orderColumn` | Order column. Default: `order_column`                                |
