# Component

Add your own `Vue` component.

```php
$form->component('my-component')
    ->cols(3);
```

Read the [Extend Vue](/guide/basics/vue.html#bootstrap-vue) section to learn how to register your own Vue components.

## Methods

| Method  | Description                                                       |
| ------- | ----------------------------------------------------------------- |
| `prop`  | Add a prop that should be passed to your `Vue` component.         |
| `props` | Add multiple props that should be passed to your `Vue` component. |
| `cols`  | Cols of the component.                                            |
