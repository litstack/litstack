<template>
    <fj-form-item :field="field" :model="model" no-min-max>
        <b-input-group>
            <b-form-input
                :value="model[`${field.id}Model`]"
                @input="changed"
                type="range"
                number
                :min="field.min"
                :max="field.max"
                :step="field.step"
                v-bind:disabled="readonly"
            />
            <b-input-group-append is-text class="text-monospace">
                {{ value }}
            </b-input-group-append>
        </b-input-group>

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
        },
        readonly: {
            required: true,
            type: Boolean
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
