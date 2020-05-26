<template>
    <component
        :is="wrapperComponent.name"
        v-bind="{ ...$attrs, ...wrapperComponent.props }"
    >
        <component
            v-for="(child, key) in children"
            :is="child.name"
            :key="key"
            v-bind="{ ...$attrs, ...child.props }"
            v-on="$listeners"
        />
    </component>
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
