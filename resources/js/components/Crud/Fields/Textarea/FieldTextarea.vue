<template>
    <lit-base-field
        :field="field"
        :model="model"
        :value="value"
        :max="field.maxChars"
        v-slot:default="{ state }"
    >
        <b-form-textarea
            :value="value"
            :placeholder="field.placeholder"
            :rows="field.rows"
            :max-rows="field.maxRows"
            :maxlength="field.maxChars"
            :state="state"
            v-bind:readonly="field.readonly"
            @input="$emit('input', $event)"
        />

        <slot />
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldTextarea',
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
