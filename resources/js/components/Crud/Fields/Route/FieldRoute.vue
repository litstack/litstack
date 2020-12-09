<template>
    <lit-base-field :field="field" :model="model">
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
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldRoute',
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
