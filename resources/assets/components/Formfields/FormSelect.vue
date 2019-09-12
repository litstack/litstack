<template>
    <fj-form-item :field="field">
        <b-select :value="model[`${field.id}Model`]" :options="options" @input="changed"/>
        <slot />
    </fj-form-item>
</template>

<script>
export default {
    name: 'FormSelect',
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
        changed(val) {
            console.log('changed', val)
            this.model[`${this.field.id}Model`] = val
            this.$emit('changed')
        },
        handle(val) {
            //t

        }
    },
    computed: {
        options() {
            return this.field.options
        }
    }
};
</script>

<style lang="css"></style>
