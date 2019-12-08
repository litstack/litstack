# Relation

A relation picker.

Example

```php
[
    'id' => 'article',
    'model' => \App\Models\Article::class, // can also be a query builder
    'many' => true, // hasOne or hasMany
    'preview' => [
        [
            'type' => 'image',
            'key' => 'image.conversion_urls.sm'
        ],
        'title', // Shown keys in preview table
    ],
    'type' => 'relation',
    'title' => 'Articles',
    'hint' => 'Choose articles that should be shown on the front Page.',
    'width' => 12,
    'button' => 'Add Article' // custom text for the button
]
```
