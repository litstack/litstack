<template>
    <b-row class="mt-3">
        <fj-field
            v-for="(field, key) in fields"
            :key="key"
            :field="field"
            :model-id="model.id"
            :model="block"
            v-on="$listeners"
        />
    </b-row>
</template>

<script>
export default {
    name: 'FieldBlockForm',
    props: {
        block: {
            required: true,
            type: Object
        },
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        },
        fields: {
            type: Array,
            required: true
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';
</style>
