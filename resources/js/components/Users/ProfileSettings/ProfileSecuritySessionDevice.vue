<template>
    <span>
        <fa-icon :icon="item.device" class="mr-1" />

        {{
            __('profile.browser_on_os', { browser: item.browser, os: item.os })
        }}
    </span>
</template>

<script>
export default {
    name: 'ProfileSecuritySessionDevice',
    props: {
        item: {
            required: true,
            type: Object
        }
    }
};
</script>
