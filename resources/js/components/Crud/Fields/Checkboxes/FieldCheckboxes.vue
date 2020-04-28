<template>
    <fj-form-item :field="field" :model="model">
        <b-checkbox-group
            :checked="value"
            :options="field.options"
            @input="changed"
        />
    </fj-form-item>
</template>

<script>
import methods from '../methods';

export default {
    name: 'FieldCheckboxes',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            value: []
        };
    },
    beforeMount() {
        this.init();
    },
    methods: {
        ...methods,
        changed(value) {
            this.setValue(value);
            this.$emit('changed', value);
        }
    }
};
</script>
