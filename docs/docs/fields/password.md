# Password

A `password` input Field.

```php
$form->password('password')
    ->title('Password');
```

## Confirm Form

Update or create form requires a password confirmation.

```php{3}
$form->password('password')
    ->title('Password')
    ->confirm();
```

## Confirm Password

Password fields can be used to update and confirm user passwords using the following `rules`.

```php
$modal->password('password')
    ->title('New Password')
    ->rules('required', 'min:5')
    ->minScore(0);

$modal->password('password_confirmation')
    ->rules('required', 'same:password')
    ->dontStore()
    ->title('New Password')
    ->noScore();
```

## Methods

| Method          | Description                                                                    |
| --------------- | ------------------------------------------------------------------------------ |
| `title`         | The title description for this field.                                          |
| `placeholder`   | The placeholder for this field.                                                |
| `hint`          | A short hint that should describe how to use the field.`                       |
| `width`         | Width of the field.                                                            |
| `confirm`       | Requires the user to type in the current passwort to confirm update or create. |
| `dontStore`     | The password wont be stored, but only to use validation rules.                 |
| `noScore`       | Shouldn't display the password score.                                          |
| `minScore`      | Minimum password score.                                                        |
| `rules`         | Field rules that should be applied to the update and create form.              |
| `creationRules` | Field rules that should be applied to the create form.                         |
| `updateRules`   | Field rules that should be applied to the update form.                         |
