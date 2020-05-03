<template>
    <span>
        <fa-icon :icon="item.device" />

        {{ item.os }}
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
