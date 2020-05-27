# Relation

A relation picker. Relation picker can be created for any relation of your model.

For the relation Field only the name of the relation must be specified, the type of the relation is automatically recognized and displayed accordingly.

The relations Field can only be used for **Crud Models** and not in **Forms** or **Blocks**. For Forms or Blocks the [oneRelation](/docs/fields/one_relation.html) or [manyRelation](/docs/fields/many_relation.html) field can be used.

```php
$form->relation('articles')
    ->title('Articles')
    // An optional query builder can be defined:
    ->query(Articles::where('created_by', fjord_user()->id))
    ->confirm() // User gets asked to confirm unlinking the relation.
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

## Sortable

If the relation should be sortable, the related query in your Model must be sorted by an `orderColumn`.

```php
public function articles()
{
    return $this->hasMany('App/Models/Article')->orderBy('order_column');
}
```

Now the sortable attribute can be added:

```php
$form->relation('articles')
    ->title('Articles')
    ->sortable()
    ->preview(function ($table) {
        $table->col('title');
    });
```

## Methods

| Method         | Description                                                                   |
| -------------- | ----------------------------------------------------------------------------- |
| `title`        | The title description for this field.                                         |
| `hint`         | A short hint that should describe how to use the field.`                      |
| `width`        | Width of the field.                                                           |
| `query`        | Initial query builder for the selectable relations. (optional)                |
| `preview`      | A closure to define the table preview of the corresponding relation.          |
| `previewQuery` | Modify preview query with eager loads and accessors that should be displayed. |
| `confirm`      | Modal pops when unlinkin the relation and asks to confirm.                    |
| `sortable`     | Sortable relation (only works for `many` relations).                          |
