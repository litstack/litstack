# List

A list field with nested items. It can be used to build **navigations** with nested entries.

```php
$form->list('menue')
    ->title('Menue')
    ->previewText('{title}')
    ->form(function($form) {
        $form->input('title')
            ->title('Title');
    });
```

:::tip
Add creation **rules** to the fields of your list to prevent the creation of empty items
:::

### Preparing the Model

List fields can be added to CRUD-Models as well as to forms. Lists don't need a dedicated column in the model, as they are stored in a database table centrally.

```php
public function menue()
{
    return $this->listItems('menue');
}
```

You can now receive the list items like this:

```php
Post::find($id)->menue;
```

## Max Depth

The maximum depth of the list levels can be specified with `maxDepth`. The default value is `3`.

```php{3}
$form->list('menue')
    ->title('Menue')
    ->maxDepth(4)
    ->previewText('{title}')
    ->form(function($form) {
        $form->input('title')
            ->title('Title');
    });
```

## Retreive Data

## Methods

| Method        | Description                                |
| ------------- | ------------------------------------------ |
| `title`       | The title description for this form field. |
| `form`        | The corresponding form to the list item.   |
| `previewText` | Title that describes the form.             |
| `maxDepth`    | Title that describes the form.             |
