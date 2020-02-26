<template>
    <fj-form-item :field="field" :model="model" :value="value">

        <b-form-input
            :value="model[`${field.id}Model`]"
            @input="changed"
            type="range"
            :min="field.min"
            :max="field.max"
            :step="field.step">
        </b-form-input>

        <slot />
    </fj-form-item>
</template>

<script>
export default {
    name: 'FormRange',
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
            value: this.model[`${this.field.id}Model`]
        };
    },
    methods: {
        changed(value) {
            this.value = value;
            this.model[`${this.field.id}Model`] = value;
            this.$emit('changed');
        }
    }
};
</script>
