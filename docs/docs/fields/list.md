# List

A list field with nested items.

```php
$form->list('menue')
    ->title('Menue')
    ->previewText('{title}')
    ->form(function($form) {
        $form->input('title')
            ->title('Title');
    })->;
```

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

## Methods

| Method        | Description                                |
| ------------- | ------------------------------------------ |
| `title`       | The title description for this form field. |
| `form`        | The corresponding form to the list item.   |
| `previewText` | Title that describes the form.             |
