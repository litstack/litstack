<template>
    <fj-form-item :field="field" :model="model">

        <b-input-group :size="field.size" :prepend="field.prepend" :append="field.append">
            <b-input
                class="form-control"
                :value="model[`${field.id}Model`]"
                :placeholder="field.placeholder"
                :type="field.input_type"
                @input="changed"/>
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
        },
    },
    methods: {
        changed(value) {
            this.model[`${this.field.id}Model`] = value
            this.$emit('changed')
        }
    }
};
</script>
