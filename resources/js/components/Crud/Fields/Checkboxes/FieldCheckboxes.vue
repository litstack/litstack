<template>
    <fj-base-field :field="field" :model="model">
        <b-checkbox-group
            :checked="value"
            :options="field.options"
            @input="$emit('input', $event)"
            class="fj-form-item-checkboxes"
        />
    </fj-base-field>
</template>

<script>
export default {
    name: 'FieldCheckboxes',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        },
        value: {
            required: true
        }
    }
};
</script>

<style lang="scss" scoped>
@import '@fj-sass/_variables';
.fj-form-item-checkboxes {
    height: $button-md-height / 1.5;
    align-items: center;
}
</style>
