<template>
    <component
        :is="field.component"
        :readonly="readonly"
        :field="field"
        :model="model"
        v-bind="field.props ? field.props : {}"
        @changed="changed(field, model)"
    />
</template>

<script>
import { mapGetters } from 'vuex';
import FjordModel from '@fj-js/eloquent/fjord.model';

export default {
    name: 'CrudShowFormItem',
    props: {
        model: {
            required: true
        },
        field: {
            required: true,
            type: Object
        }
    },
    methods: {
        changed(field, model) {
            if (!this.hasChanged(model, field)) {
                this.$store.commit('REMOVE_MODELS_FROM_SAVE', {
                    model,
                    id: field.id
                });
            } else {
                this.$store.commit('ADD_MODELS_TO_SAVE', {
                    model,
                    id: field.id
                });
            }
        },
        hasChanged(model, field) {
            if (
                Array.isArray(model[`${field.id}Model`]) &&
                Array.isArray(model.getOriginalModel(field))
            ) {
                return !Array.equals(
                    model[`${field.id}Model`],
                    model.getOriginalModel(field)
                );
            }
            return model.getOriginalModel(field) != model[`${field.id}Model`];
        }
    },
    computed: {
        ...mapGetters(['crud', 'form']),
        readonly() {
            return (
                this.field.readonly === true ||
                !this.form.config.permissions.update
            );
        }
    }
};
</script>
