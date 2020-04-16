<template>
    <component
        :is="field.component"
        :field="field"
        :model="model"
        :readonly="readonly"
        @changed="changed"
    />
</template>

<script>
export default {
    name: 'CrudField',
    props: {
        model: {
            required: true
        },
        field: {
            required: true,
            type: Object
        },
        readonly: {
            required: true,
            type: Boolean
        }
    },
    beforeMount() {
        //console.log('M', this.model);
    },
    methods: {
        changed() {
            if (
                this.model.getOriginalModel(this.field) ==
                this.model[`${this.field.id}Model`]
            ) {
                this.$store.commit('REMOVE_MODELS_FROM_SAVE', {
                    model: this.model,
                    id: this.field.id
                });
            } else {
                this.$store.commit('ADD_MODELS_TO_SAVE', {
                    model: this.model,
                    id: this.field.id
                });
            }
        }
    }
};
</script>

<style></style>
