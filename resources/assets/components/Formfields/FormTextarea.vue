<template>
    <fj-form-item :field="field">

        <b-form-textarea
            v-model="model[`${field.id}Model`]"
            :placeholder="field.placeholder"
            :rows="field.rows"
            :max-rows="field.max_rows"
            @input="changed">
        </b-form-textarea>

        <!--<fj-form-language :field="field"/>-->

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
