<template>
    <fj-form-item :field="field" :model="model">
        <b-checkbox-group
            :checked="this.model[`${this.field.id}Model`]"
            :options="field.options"
            @input="changed"
        />
    </fj-form-item>
</template>

<script>
export default {
    name: 'FormCheckboxes',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        },
        readonly: {
            required: true,
            type: Boolean
        }
    },
    methods: {
        changed(val) {
            this.model[`${this.field.id}Model`] = val;
            this.$emit('changed');
        }
    }
};
</script>
