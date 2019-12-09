# Relation

A picker for hasMany Relations.

Example

```php
[
    'id' => 'cars',
    'type' => 'hasMany',
    'model' => \App\Models\Car::class,
    'foreign_key' => 'booking_id',
    'form' => require fjord_resource_path('crud/cars.php'),
    'title' => 'Select Cars',
    'hint' => 'Select Cars',
    'width' => 12,
],
```

| Key           | Required | Description                          |
| ------------- | -------- | ------------------------------------ |
| `id`          | true     | The title of the hasMany relation    |
| `type`        | true     | `hasMany`                            |
| `model`       | true     | The related model's class            |
| `foreign_key` | true     | The foreign key on the related model |
| `form`        | true     | The CRUD-Form of the related model   |
| `title`       | true     | A title for the form                 |
| `hint`        | false    | A hint for the user                  |
| `width`       | false    | The width of the form                |
