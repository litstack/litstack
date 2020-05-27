# Field Introduction

Fields can be used to make database data easily editable. A field is attached to a column of the database, or a relation and with the appropriate configuration your model will editable in a flash.

## Basics

When you create a field you must first specify the `attribute` that the field refers to. Like in the example here an input field refers to `first_name`.

```php
$form->input('first_name');
```

Every Field requires a title.

```php
$form->input('first_name')->title('Firstname');
```

Furthermore the width can be specified either in **12** columns as in bootstrap, or in fractions. The fractions are converted into columns.

```php
$form->input('first_name')->title('Firstname')->width(4);
// Is the same as:
$form->input('first_name')->title('Firstname')->width(1 / 3);
```
