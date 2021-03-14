<template>
    <lit-base-field :field="field" :model="model">
        <b-radio-group
            :checked="value"
            :options="field.options"
            :stacked="field.stacked"
            :disabled="field.readonly"
            @input="$emit('input', $event)"
        />
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldRadio',
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
};
</script>
