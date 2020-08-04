# Configuration

A configuration file can be found in the laravel config directory `config/fjord.php`.

## Basics

In the following some basic configurations for the admin backend are explained. All specific configurations are explained in the corresponding documentation.

Basic configuration keys:

-   `route_prefix` Backend route prefix.
-   `default_route` Redirect after login. `route_prefix` is already prepended.
-   `login.username` Allow logging in using username or email.
-   `translatable` Controls the multilingualism of the admin interface.

## Styles And Scripts

Styles and Scripts can easily be added to your the application:

```php
use Fjord\Support\Facades\FjordApp;

FjordApp::style('path/to/your/style.css');
FjordApp::script('path/to/your/script.js');
```
