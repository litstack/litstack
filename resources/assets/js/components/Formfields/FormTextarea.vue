<template>
    <fj-form-item :field="field">


        <textarea
            class="form-control"
            :id="field.id"
            :rows="field.rows"
            :placeholder="field.placeholder"
            v-model="text"/>
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
    data() {
        return {text: ''}
    },
    beforeMount() {
        this.text = this.model[`${this.field.id}Model`]
    },
    watch: {
        text(val) {
            this.model[`${this.field.id}Model`] = val
            this.$emit('changed')
        }
    }
};
</script>

<style lang="css"></style>
