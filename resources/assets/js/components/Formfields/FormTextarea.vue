<template>
    <fj-form-item :field="field">

        <textarea
            class="form-control"
            :id="field.id"
            :rows="field.rows"
            :placeholder="field.placeholder"
            :value="model[`${field.id}Model`]"
            @input="changed"/>
        </textarea>

        <fj-form-language :field="field"/>

        <slot />

    </fj-form-item>
</template>

<script>
export default {
    name: 'FormTextarea',
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
