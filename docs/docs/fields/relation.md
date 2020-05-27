# Relation

A relation picker. Relation picker can be created for any relation of your model.

For the relation Field only the name of the relation must be specified, the type of the relation is automatically recognized and displayed accordingly.

The relations Field can only be used for **Crud Models** and not in **Forms** or **Blocks**. For Forms or Blocks the [oneRelation](/docs/fields/one_relation.html) or [manyRelation](/docs/fields/many_relation.html) field can be used.

```php
$form->relation('articles')
    ->title('Articles');
```

In the Model:

```php
public function articles()
{
    return $this->hasMany('App/Models/Article');
}
```

## Custom Preview

By default, the table configuration from the config of the related model is used. However, it often happens that they display a lot of data. For a relation it is enough to show only a few columns - just to show clear which model is involved. For this case the table can be configured directly with `preview`.

```php
$form->relation('articles')
    ->title('Articles')
    ->preview(function ($table) {
        // Build the preview table in here.
        $table->col('title'); // In this case we are showing the article title.
    });
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
        $table->col('Title')->value('{title}');
    });
```

## Filter

With a **filter** you can specify which models can be selected for a relation.

```php
$form->relation('articles')
    ->title('Articles')
    ->filter(function($query) {
        $query->where('created_by', fjord_user()->id);
    });
```

## Eager Loading & Appending Accessors

With query, **relationships** & **accessors** that should be displayed can be **eager loaded** or **appended**.

```php
$form->relation('articles')
    ->title('Articles')
    ->query(function($query) {
        $query->with('author')->append('comments_count');
    })
    ->preview(function ($table) {
        $table->col('Author')->value('{author.first_name} {author.last_name}');
        $table->col('Comments')->value('{comments_count} comments');
    });
```

## Allow Direct Delete

To switch off the modal in which deleting a relation is confirmed, `confirm` must be set to false.

```php
$form->relation('articles')
    ->title('Articles')
    ->confirm(false);
```

## Methods

| Method          | Description                                                                   |
| --------------- | ----------------------------------------------------------------------------- |
| `title`         | The title description for this field.                                         |
| `hint`          | A short hint that should describe how to use the field.`                      |
| `width`         | Width of the field.                                                           |
| `filter`        | Initial query builder for the selectable relations.                           |
| `preview`       | A closure to define the table preview of the corresponding relation.          |
| `query`         | Modify preview query with eager loads and accessors that should be displayed. |
| `confirm`       | Modal pops when unlinkin the relation and asks to confirm. (default: `true`)  |
| `sortable`      | Sortable relation (only works for `many` relations).                          |
| `showTableHead` | Whether the table head should be shown. (default: `false`)                    |
