<template>
    <fj-base-component :component="wrapperComponent" v-bind="{ ...$attrs }">
        <fj-base-component
            v-for="(component, key) in children"
            :component="component"
            :key="key"
            v-bind="$attrs"
            v-on="$listeners"
        />
    </fj-base-component>
</template>

<script>
export default {
    name: 'FieldWrapper',
    props: {
        wrapperComponent: {
            type: Object,
            required: true
        },
        children: {
            type: Array,
            required: true
        }
    }
};
</script>
