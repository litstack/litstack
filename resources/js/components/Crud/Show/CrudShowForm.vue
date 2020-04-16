<template>
    <b-col :cols="cols">
        <b-card :title="title" class="mb-4">
            <form class="row" style="margin-bottom: -1.5em;">
                <template v-for="id in fieldIds">
                    <div
                        :class="fieldWidth(getFieldById(id))"
                        v-if="getFieldById(id)"
                    >
                        <component
                            :is="getFieldById(id).component"
                            :readonly="readonly(getFieldById(id))"
                            :field="getFieldById(id)"
                            :model="getModelByFieldId(id)"
                            @changed="
                                changed(getFieldById(id), getModelByFieldId(id))
                            "
                        />
                    </div>
                </template>
            </form>
            <!--<fj-fjord-form :ids="fieldIds" :model="model" />-->
        </b-card>
    </b-col>
</template>

<script>
import { mapGetters } from 'vuex';
import FjordModel from '@fj-js/eloquent/fjord.model';

export default {
    name: 'CrudShowForm',
    props: {
        model: {
            required: true,
            type: Object
        },
        config: {
            required: true,
            type: Object
        },
        fieldIds: {
            type: Array,
            required: true
        },
        title: {
            type: String
        },
        cols: {
            type: Number,
            default() {
                return 12;
            }
        }
    },
    data() {
        return {
            preparedModels: []
        };
    },
    beforeMount() {
        this.init();

        this.$bus.$on('modelLoaded', () => {
            this.init();
        });
    },
    methods: {
        init() {
            let model = this.model || this.crud.model;

            if (model instanceof FjordModel) {
                this.preparedModels = [model];
            } else {
                this.preparedModels = model.items.items;
            }
        },
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
        },
        getFieldById(id) {
            for (let i in this.preparedModels) {
                let field = this.preparedModels[i].getFieldById(id);
                if (field) {
                    return field;
                }
            }
        },
        getModelByFieldId(id) {
            for (let i in this.preparedModels) {
                let field = this.preparedModels[i].getFieldById(id);
                if (field) {
                    return this.preparedModels[i];
                }
            }
        },
        fieldWidth(field) {
            return field.cols !== undefined ? `col-${field.cols}` : 'col-12';
        },
        readonly(field) {
            return (
                field.readonly === true || !this.form.config.permissions.update
            );
        }
    },
    computed: {
        ...mapGetters(['crud', 'form'])
    }
};
</script>
