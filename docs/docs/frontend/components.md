# Vue Components

[[toc]]

The package comes along with **bootstrap-vue** and a few components that can be used to build pages that fit the package's style. To ensure that users of the admin interface can find their way around quickly and easily on all sites, it is recommended to follow the following guidelines.

The package's components have the prefix `fj-`.

## Custom Pages

Custom pages for the admin panel consist of a `container`, the topbar `navigation` and a `header`. The following content can be created as you like.

The following example shows how the root template of a page looks like.

```javascript
<template>
    <fj-container>
        <fj-navigation/>
        <fj-header :title="My Page"/>

        // Build your page here.
    </fj-container>
</template>

<script>
export default {
    name: "MyPage"
}
</script>
```

It is recommended to wrap your page content with a b-row and fj-col components.

```javascript
<template>
    <fj-container>
        ...
        <b-row>
            <fj-col :width="1/3">...</fj-col>
            <fj-col :width="1/3">...</fj-col>
        </b-row>
    </fj-container>
</template>
```

## Navigation

The topbar navigation is designed to make it easier for the user to navigate through the interface and display important controls while scrolling.

```javascript
<fj-navigation />
```

### Go Back

In the topbar navigation a **back** button can be displayed. All pages in admin application should be reached via at most one more link from the main navigation, i.e. an **overview** page and the **detail** page.

To display the back link a text for the button and a link must be passed as prop to the navigation.

```php
<fj-navigation :back="items" :back-text="Items"/>
```

### Controls

To display controls in the tooltip, simply specify an array with component names.

```javascript
<fj-navigation :controls="['my-topbar-control']" />
```

The components for the controls must use the bootstrap `b-dropdown-item` as a root component like so:

```javascript
<template>
    <b-dropdown-item @click="something">
        Action
    </b-dropdown-item>
</template>

<script>
export default {
    name: "MyTopbarControl",
    methods: {
        something() {
            // Do your logic here...
        }
    }
}
</script>
```

Furthermore, the `left` and `right` slot can be used to display buttons directly.

```php
<fj-navigation>
    <b-button slot="left" variant="primary">
        Left Action
    </b-button>
    <b-button slot="right" variant="primary">
        Right Action
    </b-button>
</fj-navigation>
```

## Header

The page header consists of an `h3` header. The title can either be specified via the prop `title` or designed in the main slot.

As well the header can contain controls that should be displayed under the header. The controls are displayed with the slots `actions` and `actions-right`.

```javascript
<fj-header title="My Page">
    <b-button slot="actions">
        Left Action
    </b-button>
    <b-button slot="actions-right">
        Right Action
    </b-button>
<fj-header>
```

## Spinner

The spinner can be displayed with the component `fj-spinner`.

```javascript
<template v-if="busy">
    <fj-spinner />
</template>
```
