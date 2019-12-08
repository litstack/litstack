# Configuration

The Fjord-configuration file can be found in the laravel config directory `config/fjord.php`.

## Basics

In the following are some basic configurations for the Fjord backend explained. All specific configurations are explained in the corresponding documentation.

Basic configuration keys:

-   `route_prefix` Backend route prefix.

-   `resource_path` Path to the fjord resources.

-   `navigation_path` Navigation folder name in fjord resources.

-   `layout` Can be `horizontal` or `vertical`. Detailed explaination below.

-   `default_route` Redirect after login. `route_prefix` is already prepended.

### Layout

Fjord brings you the choice between two different backend layouts.

-   `horizontal`

-   `vertical`

The **horizontal** navigation is usefull for Websites with a horizontal Navigaion. In this case the backend navigation should be copied from the public site navigation so that every admin user can easily find his way around and understands where to find which data.

The `vertical` navigation is usefull for Applications where the focus is on managing data from the database.
