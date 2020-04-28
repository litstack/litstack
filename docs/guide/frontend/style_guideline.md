# Style Guideline

Fjord comes along with all **bootstrap-vue** components with a few components that can be used to build pages that fit the Fjord style. To ensure that users of the Fjord interface can find their way around quickly and easily on all sites, it is recommended to follow the following guidelines.

All Fjord components have the prefix `fj-`.

## Components

### Container

`fj-contaier` should be used as the root component of each page.

```javascript
<template>
    <fj-container>
        // Build your page here.
        <b-row>...</b-row>
    </fj-container>
</template>

<script>
export default {
    name: "MyPage"
}
</script>
```

### Header

Every page should have a page-header, for this there is the `fj-header` component. A `back-route` and a `back-text` can be set to show a go back button above the title.

```javascript
<template>
    <fj-container>
        <fj-header
            title="My Page"
            back-text="All Pages"
            :back-route="`${Fjord.baseURL}pages-overview`"/>

        // Build your page here.
        <b-row>...</b-row>
    </fj-container>
</template>

<script>
export default {
    name: "MyPage"
}
</script>
```

Furthermore action buttons can be placed under the title. This is another standard set by Fjord to keep pages consistent and make it easy for the user to find his way around. It is recommended to use **small** for the button size in the header.

```javascript
<fj-header title="My Page">
    <template slot="actions">
        // These buttons apply on the left side.
        <b-button size="sm" />
    </template>

    <template slot="actions-right">
        // These buttons apply on the right side.
        <b-button size="sm" />
    </template>
</fj-header>
```

### Spinner

The Fjord spinner can be displayed with the component `fj-spinner`.

```javascript
<template v-if="busy">
    <fj-spinner />
</template>
```
