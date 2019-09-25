<template>
    <fj-form-item :field="field" :model="model" :value="value">
        <b-input-group
            :size="field.size"
            :prepend="field.prepend"
            :append="field.append"
        >
            <b-input
                class="form-control"
                :value="model[`${field.id}Model`]"
                :placeholder="field.placeholder"
                :type="field.input_type"
                :maxlength="field.max"
                :required="field.required"
                @input="changed"
            />
        </b-input-group>

        <slot />
    </fj-form-item>
</template>

<script>
export default {
    name: 'FormInput',
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
