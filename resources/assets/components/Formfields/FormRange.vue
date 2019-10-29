<template>
    <fj-form-item :field="field" :model="model" :value="value">
        <!-- <b-input-group :size="field.size">

            <b-input
                class="form-control"
                :value="model[`${field.id}Model`]"
                :placeholder="field.placeholder"
                :type="field.input_type"
                :maxlength="field.max"
                :required="field.required"
                @input="changed"
            />

        </b-input-group> -->

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
