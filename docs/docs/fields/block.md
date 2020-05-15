# Block

Blocks can be added to CRUD-Models as well as to Collections like Pages. Blocks don't need a dedicated column in the model, as they are stored in a centrally.

Blocks are used to map repetitive content. They can contain any number of so called repeatables, which you can string together in any order.
A Repeatable can be regarded as a separate individual form. As well a preview table can be set for every block. If Field values are to be displayed as preview, they must be written in **curly brackets**.

```php
$form->block('content')
    ->title('Content')
    ->repeatables(function($repeatables) {

        // Add as many repeatables as you want.
        $repeatables->add('text', function($form, $preview) {
            // The block preview.
            $preview->col('{text}');

            // Containing as many form fields as you want.
            $form->input('text')
                ->title('Text');
        });

        $repeatables->add('image', function($form, $preview) {
            $preview->col('{}');

            $form->image('image')
                ->title('Image');
        });
    });
```

### Preparing the Model

Setting your model up for using blocks is simple. Any block gets it's dedicated relation method:

```php
public function content()
{
    return $this->blocks('content');
}
```

You can now receive the block data like this:

```php
Post::find($id)->content;
```

## Methods

| Method        | Description                                        |
| ------------- | -------------------------------------------------- |
| `title`       | The title description for this field.              |
| `repeatables` | A closure where all repeatable blocks are defined. |
| `cols`        | Cols of the field.                                 |
