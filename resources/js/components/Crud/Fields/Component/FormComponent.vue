<template>
    <component
        :is="field.comp.name"
        v-bind="field.comp.props"
        :field="field"
        :model="model ? model[`${field.id}Model`] : null"
        :readonly="readonly"
    />
</template>

<script>
export default {
    name: 'FormComponent',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true
        },
        readonly: {
            required: true,
            type: Boolean
        }
    }
};
</script>
