# Field Introduction

Fields can be used to make database data easily editable. A field is attached to a column of the database, or a relation and with the appropriate configuration your model will editable in a flash.

## Basics

When you create a field you must first specify the `attribute` that the field refers to. Like in the example here an input field refers to `first_name`.

```php
$form->input('first_name');
```

Furthermore, a `title` and the number of `cols` can be specified for each field.

```php
$form->input('first_name')->title('Firstname')->cols(6);
```
