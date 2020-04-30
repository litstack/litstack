<template>
    <b-row class="mt-3">
        <fj-field
            v-for="(field, key) in block.fields"
            :key="key"
            :field="field"
            :model-id="model.id"
            :model="block"
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
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';
</style>
