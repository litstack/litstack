# Fjord Page

[[toc]]

Fjord Pages are the fundamental part of fjord. They provide a convenient and yet powerfull way to configure pages for the Vue application in PHP. They can be used to integrate **Blade Views**, **Vue components** or ready-made components such as **charts** or form fields for models.

Fjord pages are used to configure forms with fields, index pages, dashboards with charts or basicly any kind of page for the Fjord backend.

## Create a Page

A page can be simply created by returning an instance of `Fjord\Page\Page` like so:

```php
namespace FjordApp\Controllers;

use Fjord\Page\Page;

class MyPageController
{
    public function __invoke()
    {
        $page = new Page;

        return $page->title('My Page');
    }
}
```

This will show an empty page with the title `My Page`:

![Page with Title](./screens/page_title.png 'Page with Title')

## Adding Blade Views

You can easily integrate your own blade components with using `view`:

```php
$page = new Page;

$page->view('hello');

return $page->title('My Page');
```

```php
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h4 class="pb-2">Hello World!</h4>
        </div>
    </div>
</div>
```

And voila we have our first Page with content saying `Hello World!` using pure Blade.

![Page with View](./screens/page_view.png 'Page with View')

## Adding Vue Components

Just like blade views, Vue components can be easily integrated. To do this, the **name** of the Vue components must be passed as the first parameter to the `component method:

```php
$page->component('my-component');
```

You can also simply pass a **prop** to the component:

```php
$page->component('my-component')->prop('color', 'green');
```

Or bind an array that should be passed as **props** to the component:

```php
$page->component('my-component')->bind([
    'title' => 'My Title',
    'color' => 'green'
]);
```

## Binding Global Page Data

You may want to bind global data to all views and components contained in the page. This can be done by passing an array containing the data to the `bind` method like this:

```php
$page->bind($data);
```

The data is now accessible in Blade Views and is passed to Vue components as props. However, you might want to pass data only to Blade Views or only to Vue components. You can use `bindToView` and `bindToVue` in that case:

```php
$page->bindToView($data);
$page->bindToVue($data);
```
