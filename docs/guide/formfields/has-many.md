# Relation

A picker for hasMany Relations.

Example

```php
[
    'id' => 'employees',
    'type' => 'hasMany',
    'model' => \App\Models\Employee::class,
    'foreign_key' => 'department_id',
    'preview' => [
        [
            'key' => '{fullName}',
            'label' => 'Name'
        ],
    ],
    'title' => 'Staff',
    'hint' => 'Select Staff',
    'width' => 12,
],
```

| Key           | Required | Description                              |
| ------------- | -------- | ---------------------------------------- |
| `id`          | true     | The title of the hasMany relation        |
| `type`        | true     | `hasMany`                                |
| `model`       | true     | The related model's class                |
| `foreign_key` | true     | The foreign key on the related model     |
| `preview`     | true     | Array of fields that should be previewed |
| `title`       | true     | A title for the form                     |
| `hint`        | false    | A hint for the user                      |
| `width`       | false    | The width of the form                    |
