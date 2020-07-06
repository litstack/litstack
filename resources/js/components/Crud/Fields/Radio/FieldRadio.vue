<template>
    <fj-base-field :field="field" :model="model">
        <b-radio-group
            :checked="value"
            :options="field.options"
            :stacked="field.stacked"
            @input="$emit('input', $event)"
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
