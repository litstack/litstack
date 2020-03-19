# Block

Blocks can be added to CRUD-Models as well as to Collections like Pages. Blocks don't need a dedicated column in the model, as they are stored in a centrally.

Blocks are used to map repetitive content. They can contain any number of so called repeatables, which you can string together in any order.
A Repeatable can be regarded as a separate individual form.

```php
[
    'type' => 'block',
    'id' => 'content',
    'title' => 'Content',
    'width' => 12,
    'repeatables' => [
        'text', 'image'
    ],
]
```

## Preparing the Model

Setting your model up for using blocks is simple. Any block gets it's dedicated method:

```php
<?php

namespace App\Models;

class Post extends FjordModel
{
    ...

    public function content()
    {
        return $this->blocks('content');
    }
}
```

You can now receive the block data like this:

```php
Post::find($id)->content();
```
