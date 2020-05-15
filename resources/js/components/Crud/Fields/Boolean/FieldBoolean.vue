<template>
    <fj-form-item :field="field" :model="model">
        <b-form-checkbox
            v-model="value"
            name="check-button"
            switch
            @input="changed"
            class="fj-form-item-boolean"
        />

        <slot />
    </fj-form-item>
</template>

<script>
import methods from '../methods';

export default {
    name: 'FieldBoolean',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true
        }
    },
    data() {
        return {
            value: null,
            original: null
        };
    },
    beforeMount() {
        this.init();
    },
    methods: {
        ...methods,
        changed(value) {
            if (value === null) return;
            if (value === undefined) value = false;
            this.setValue(value);
            this.$emit('changed', value);
        }
    }
};
</script>

<style lang="scss" scoped>
@import '@fj-sass/_variables';
.fj-form-item-boolean {
    height: $button-md-height / 1.5;
    align-items: center;
}
</style>
