<template>
    <component :is="component" v-bind="props" />
</template>

<script>
export default {
    name: 'Slot',
    props: {
        props: {
            type: Object,
            default() {
                return {};
            }
        },
        component: {
            type: String
        }
    }
};
</script>
