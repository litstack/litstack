<template>
    <fj-form-item :field="field">

        <b-checkbox-group
            :checked="value"
            :options="options"
            @input="changed"
        />

    </fj-form-item>
</template>

<script>
export default {
    name: 'FormCheckboxes',
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
        return {
            value: null
        }
    },
    beforeMount() {
        this.value = this.model[`${this.field.id}Model`]
        /*
        if(this.model.isFjordModel()) {
            this.value = JSON.parse(model[`${field.id}Model`])
        }
        */
    },
    methods: {
        changed(val) {
            console.log('changed', val)

            if(this.model.isFjordModel()) {
                this.model[`${this.field.id}Model`] = JSON.stringify(val)
            } else {
                this.model[`${this.field.id}Model`] = val
            }

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
