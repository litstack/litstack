# Dashboard

The dashboard is fully customizable. You can add Vue-Components by simply adding them to the `components` array in the `resources/fjord/dashboard.php` by name.

```php
<?php

return [
    'components' => [
        'dashboard-statistics',
        'active-users'
    ]
];
```

## Register Vue-Components

In order to use your custom components you have to [extend the Fjord-App](../extensions/setup.md) and register your components:

```js
import Fjord from 'fjord';

Vue.component(
    'dashboard-statistics',
    require('./components/dashboard/Statistics').default
);

const store = {};

new Fjord({
    store
});
```

## Handling Data

Receiving and sending data will be handled by the `DashboardController.php` in the `Controllers/Fjord` directory.

You are free to set up routes like this and methods like this:

```php
public function makeDashboardStatisticsRoute(): Void
{
    FjordRoute::get("/dashboard/statistics", self::class . "@statistics");
}

public function statistics()
{
    return Post::all();
}
```

Any method named 'make...Route' will automatically be called.

## Component Example

A simple component could look something like this:

```js
<template>
    <div class="col col-md-6">
        <div class="card">
            <div class="card-body">
                ...
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Statistics",
    data(){
        return {
            posts: []
        }
    },
    mounted() {
        this.fetchPosts();
    },
    methods: {
        async fetchPosts() {
            const { data } = await axios.get(`/dashboard/statistics`);
            this.posts = data
        }
    }
};
</script>
```
