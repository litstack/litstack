<template>
    <lit-base-field
        :field="field"
        :model="model"
        :value="value"
        v-slot:default="{ state }"
        v-on="$listeners"
    >
        <b-input-group :size="field.size">
            <b-input-group-prepend is-text v-if="field.prepend">
                <span v-html="field.prepend"></span>
            </b-input-group-prepend>

            <b-input
                ref="input"
                class="form-control lit-field-input"
                :value="value"
                :placeholder="field.placeholder"
                :type="field.type"
                :maxlength="field.max"
                :required="field.required"
                :state="state"
                :autofocus="field.autofocus"
                v-bind:readonly="field.readonly"
                @input="$emit('input', $event)"
            />

            <b-input-group-append is-text v-if="field.append">
                <span v-html="field.append"></span>
            </b-input-group-append>
        </b-input-group>

        <slot />
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldInput',
    props: {
        /**
         * Field attributes.
         */
        field: {
            required: true,
            type: Object,
        },

        /**
         * Model.
         */
        model: {
            required: true,
            type: Object,
        },

        /**
         * Field value.
         */
        value: {
            required: true,
        },
    },
};
</script>
