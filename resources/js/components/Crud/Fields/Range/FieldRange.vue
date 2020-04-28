<template>
    <fj-form-item :field="field" :model="model" no-min-max>
        <b-input-group>
            <b-form-input
                :value="value"
                @input="changed"
                type="range"
                number
                :min="field.min"
                :max="field.max"
                :step="field.step"
                v-bind:disabled="field.readonly"
            />
            <b-input-group-append is-text class="text-monospace">
                {{ value }}
            </b-input-group-append>
        </b-input-group>

        <slot />
    </fj-form-item>
</template>

<script>
import methods from '../methods';

export default {
    name: 'FieldRange',
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
            value: 0
        };
    },
    beforeMount() {
        this.init();

        if (this.value === undefined) {
            this.value = this.field.min;
        }
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
