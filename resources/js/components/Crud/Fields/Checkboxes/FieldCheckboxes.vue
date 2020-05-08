<template>
    <fj-form-item :field="field" :model="model">
        <b-checkbox-group
            :checked="value"
            :options="field.options"
            @input="changed"
            class="fj-form-item-checkboxes"
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
            value: [],
            original: []
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

<style lang="scss" scoped>
@import '@fj-sass/_variables';
.fj-form-item-checkboxes {
    height: $button-md-height / 1.5;
    align-items: center;
}
</style>
