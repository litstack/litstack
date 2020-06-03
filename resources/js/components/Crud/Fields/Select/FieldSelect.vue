<template>
    <fj-base-field :field="field" :model="model">
        <template v-if="!field.readonly">
            <b-select
                :value="value"
                :options="field.options"
                v-bind:readonly="field.readonly"
                v-on:input="$emit('input', $event)"
            />
        </template>

        <template v-else>
            <b-input class="form-control" :value="value" type="text" readonly />
        </template>
        <slot />
    </fj-base-field>
</template>

<script>
export default {
    name: 'FieldSelect',
    props: {
        /**
         * Field attributes.
         */
        field: {
            required: true,
            type: Object
        },

        /**
         * Model.
         */
        model: {
            required: true,
            type: Object
        },

        /**
         * Field value.
         */
        value: {
            required: true
        }
    }
};
</script>
