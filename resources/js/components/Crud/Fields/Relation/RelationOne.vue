<template>
    <fj-form-item :field="field" :model="model" v-bind:no-hint="!readonly">
        <template v-if="model.id">
            <div class="form-control-expand">
                <div v-if="selectedModels">
                    <fj-form-relation-index
                        :field="field"
                        :items="selectedModels"
                        :readonly="readonly"
                        :routePrefixes="routePrefixes"
                        @removeRelation="removeRelation"
                    />
                </div>
                <div v-else>
                    <fj-form-alert-empty
                        :field="field"
                        :class="{ 'mb-0': readonly }"
                    />
                </div>

                <b-button
                    variant="secondary"
                    size="sm"
                    v-b-modal="modalId"
                    v-if="!readonly"
                >
                    Select {{ field.title }}
                </b-button>
            </div>

            <slot />

            <fj-form-relation-modal
                :field="field"
                :model="model"
                :hasMany="false"
                :selectedModels="selectedModels"
                @selected="selected"
            />
        </template>
        <template v-else>
            <fj-form-alert-not-created :field="field" class="mb-0" />
        </template>
    </fj-form-item>
</template>

<script>
import TranslatableEloquent from '@fj-js/eloquent/translatable';
import TableModel from '@fj-js/eloquent/table.model';
import { mapGetters } from 'vuex';

export default {
    name: 'FormRelationOne',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        },
        readonly: {
            required: true,
            type: Boolean
        }
    },
    data() {
        return {
            relation: {},
            routePrefixes: [],
            routePrefix: '',
            selectedModel: '',
            selectedModels: {}
        };
    },
    beforeMount() {
        let relation = this.model[`${this.field.id}`];

        if (relation) {
            this.relation = new TableModel(relation);
        }

        this.selectedModel = this.field.model;
        if (this.field.type == 'morphTo') {
            this.selectedModel = this.model[this.field.morph_type];
        }

        this.selectedModels = { [this.selectedModel]: [this.relation] };
        this.routePrefixes = { [this.selectedModel]: this.field.route_prefix };
        this.routePrefix = this.field.route_prefix;
        if ('route_prefixes' in this.field) {
            this.routePrefixes = this.field.routePrefixes;
            this.routePrefix = this.field.routePrefixes[this.selectedModel];
        }
    },
    methods: {
        async selected(item, modelName) {
            console.log(item, modelName);
            switch (this.field.type) {
                case 'oneRelation':
                    try {
                        response = axios.post(
                            `${this.form.config.route_prefix}/${this.model.id}/${this.field.id}/${item.id}`
                        );
                    } catch (e) {
                        console.log(e);
                        return;
                    }
                    break;
                case 'morphTo':
                    this.model.attributes[this.field.morph_type] = modelName;
                    this.model.attributes[this.field.foreign_key] = item.id;
                    this.model[`${this.field.id}Model`] = item.attributes;
                    this.setCols();
                    this.$emit('changed', this.field, this.model);
                    break;
                case 'morphOne':
                    let response = null;
                    try {
                        if (this.relation) {
                            response = axios.put(
                                `${this.field.route}/${this.relation.id}`,
                                {
                                    [this.field.morph_type]: null,
                                    [this.field.foreign_key]: null
                                }
                            );
                        }

                        response = axios.put(`${this.field.route}/${item.id}`, {
                            [this.field.morph_type]: this.field
                                .morph_type_value,
                            [this.field.foreign_key]: this.model[
                                this.field.local_key_name
                            ]
                        });
                    } catch (e) {
                        console.log(e);
                    }
                    this.$bvToast.toast(
                        this.$t('fj.relation_added', {
                            relation: this.field.title
                        }),
                        {
                            variant: 'success'
                        }
                    );
                    break;
                case 'belongsTo':
                case 'hasOne':
                    this.model[`${this.field.id}Model`] = item.id;
                    this.$emit('changed');
                    break;
            }
            this.selectedModel = modelName;
            this.relation = item;
            this.selectedModels = { [this.selectedModel]: [this.relation] };
            if ('routes' in this.field) {
                this.route = this.field.routes[this.selectedModel];
            }
            this.$bvModal.hide(this.modalId);
        },
        async removeRelation({ id, modelName }) {
            switch (this.field.type) {
                case 'oneRelation':
                    response = axios.delete(
                        `${this.form.config.route_prefix}/${this.model.id}/${this.field.id}/${id}`
                    );
                    break;
                case 'morphOne':
                    let response = null;
                    try {
                        response = axios.put(
                            `${this.field.route}/${this.relation.id}`,
                            {
                                [this.field.morph_type]: null,
                                [this.field.foreign_key]: null
                            }
                        );
                    } catch (e) {
                        console.log(e);
                    }

                    break;
                case 'belongsTo':
                case 'hasOne':
                    this.model[`${this.field.id}Model`] = null;
                    this.relation = null;
                    this.$emit('changed');
                    break;
            }
            this.$bvModal.hide(`modal-${this.route}-${item.id}`);
        },
        setItem(item) {
            item.trash = '';
            return item;
        }
    },
    computed: {
        ...mapGetters(['form', 'baseURL']),
        modalId() {
            return `${this.model.route}-form-relation-table-${this.field.id}-${this.model.id}`;
        }
    }
};
</script>
<style lang="scss" scoped>
.table-controls {
    height: 100%;
    position: absolute;
    top: 0;
    right: 0;
    .btn-group {
        .btn {
            border-radius: 0 !important;
        }
    }
}
</style>
