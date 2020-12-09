<template>
    <lit-base-component :component="wrapperComponent" v-bind="{ ...$attrs }">
        <lit-base-component
            v-for="(component, key) in children"
            :component="component"
            :key="key"
            v-bind="$attrs"
            v-on="$listeners"
        />
    </lit-base-component>
</template>

<script>
export default {
    name: 'Wrapper',
    props: {
        wrapperComponent: {
            type: Object,
            required: true,
        },
        children: {
            type: Array,
            required: true,
        },
    },
};
</script>
