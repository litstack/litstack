Load the form data in your controller and pass it to the view:

```php
<?php

namespace App\Http\Controllers;

use Fjord\Support\Facades\Form;

class HomeController
{
    public function __invoke()
    {
        return view('home')->with([
            'data' => Form::load('pages', 'home')
        ]);
    }
}
```

And easely show the values of your fields in the view:

```php
<h1>{{ $data->title }}</h1>
```
