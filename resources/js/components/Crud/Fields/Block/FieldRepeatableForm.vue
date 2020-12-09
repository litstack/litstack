<template>
    <b-row class="mt-3">
        <lit-field
            ref="fields"
            v-for="(field, key) in fields"
            :key="key"
            :field="field"
            :model-id="modelId"
            :model="block"
            v-on="$listeners"
        />
    </b-row>
</template>

<script>
export default {
    name: 'FieldRepeatableForm',
    props: {
        block: {
            required: true,
            type: Object,
        },
        field: {
            required: true,
            type: Object,
        },
        model: {
            required: true,
            type: Object,
        },
        modelId: {
            required: true,
        },
        fields: {
            type: Array,
            required: true,
        },
    },
    mounted() {
        for (let i = 0; i < this.$refs.fields.length; i++) {
            let field = this.$refs.fields[i];
            field.$emit('setSaveJobId', this.getSaveJobId());
        }
    },
    methods: {
        getSaveJobId() {
            return [this.field.id, this.block.id].join('.');
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
</style>
