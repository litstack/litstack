<template>
    <fj-form-item :field="field" :model="model">
        <b-form-checkbox
            v-model="value"
            name="check-button"
            switch
            @input="changed"
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
            value: false,
            original: false
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

<style lang="css"></style>
