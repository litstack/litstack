# Field Introduction

Fields can be used to make database entries easily editable. A field or a relation is attached to a column of the database. With the appropriate configuration your model will editable in no time.

## Basics

When you create a field you must first specify the `attribute` that the field refers to. Like in the following example an input field refers to `first_name`.

```php
$form->input('first_name');
```

Every field requires a title.

```php
$form->input('first_name')->title('Firstname');
```

Furthermore the width can be specified either in **12** columns as in bootstrap, or in fractions. The fractions are converted into columns.

```php
$form->input('first_name')->title('Firstname')->width(4);
// Is the same as:
$form->input('first_name')->title('Firstname')->width(1 / 3);
```
