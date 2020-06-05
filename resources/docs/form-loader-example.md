Load the form data in your controller and pass it to the view:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Fjord\Support\Facades\Form;

class PageController extends Controller
{
    public function home(Request $request)
    {
        return view('home')->with([
            'data' => Form::load('pages', 'home')
        ]);
    }
}
```

And easely show the values of your fields:

```php
<h1>{{ $data->title }}</h1>
```
