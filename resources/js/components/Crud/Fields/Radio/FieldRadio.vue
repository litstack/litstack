<template>
    <fj-base-field :field="field" :model="model">
        <b-radio-group
            :checked="value"
            :options="field.options"
            @input="$emit('input', $event)"
            :stacked="field.stacked"
        />
    </fj-base-field>
</template>

<script>
export default {
    name: 'FieldRadio',
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

<style lang="scss"></style>
