<template>
    <fj-form-item :field="field">

        <b-input
            class="form-control"
            :value="model[`${field.id}Model`]"
            :placeholder="field.placeholder"
            @input="changed"/>

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

<style lang="css"></style>
