<template>
    <component
        :is="field.comp.name"
        v-bind="field.comp.props"
        :field="field"
        :model="model"
    />
</template>

<script>
export default {
    name: 'FieldComponent',
    props: {
        field: {
            required: true,
            type: Object,
        },
        model: {
            required: true,
        },
    },
};
</script>
