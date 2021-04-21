<template>
    <lit-base-field :field="field" :model="model">
        <b-checkbox-group
            v-model="selected"
            :options="field.options"
            :stacked="field.stacked"
            :disabled="field.readonly"
            class="lit-form-item-checkboxes"
        />
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldCheckboxes',
    props: {
        field: {
            required: true,
            type: Object,
        },
        model: {
            required: true,
            type: Object,
        },
        value: {
            required: true,
        },
    },
    data() {
        return {
            original: null,
            selected: [],
        };
    },
    beforeMount() {
        this.original = this.value;
        if (!_.isEmpty(this.value)) {
            this.selected = this.value;
        }
    },
    watch: {
        selected(val) {
            if (_.isEmpty(this.original) && _.isEmpty(val)) {
                this.$emit('input', this.original);
            } else {
                this.$emit('input', val);
            }
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
.lit-form-item-checkboxes {
    align-items: center;
}
</style>
