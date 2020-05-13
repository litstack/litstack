<template>
    <fj-form-item
        :field="field"
        :model="model"
        :value="value"
        v-slot:default="{ state }"
    >
        <b-form-textarea
            :value="value"
            :placeholder="field.placeholder"
            :rows="field.rows"
            :max-rows="field.max_rows"
            :maxlength="field.max"
            :state="state"
            v-bind:readonly="field.readonly"
            @input="changed"
        />

        <slot />
    </fj-form-item>
</template>

<script>
import methods from '../methods';

export default {
    name: 'FieldTextarea',
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
            value: '',
            original: ''
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
