<template>
    <fj-form-item :field="field" :model="model">

        <vue-ctk-date-time-picker
            v-model="value"
            :label="field.label"
            :format="'YYYY-MM-DD HH:mm:ss'"
            :inline="field.inline"
            :formatted="field.formatted"
            :onlyDate="field.only_date"
            color="var(--primary)"
        />

    </fj-form-item>
</template>

<script>
export default {
    name: 'FormDateTime',
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
    },
    watch: {
        value(val) {
            this.model[`${this.field.id}Model`] = val
            this.$emit('changed')
        }
    },
};
</script>

<style lang="scss">

@import '~vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';

</style>
