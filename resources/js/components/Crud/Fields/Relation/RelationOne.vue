<template>
    <fj-form-item
        :field="field"
        :model="model"
        v-bind:no-hint="!field.readonly"
    >
        <template v-if="model.id">
            <div class="form-control-expand">
                <div v-if="relation && Object.keys(relation).length > 0">
                    <fj-field-relation-index
                        :field="field"
                        :items="selectedModels"
                        :model-id="modelId"
                        :routePrefixes="routePrefixes"
                        @removeRelation="removeRelation"
                    />
                </div>
                <div v-else>
                    <fj-field-alert-empty
                        :field="field"
                        :class="{ 'mb-0': field.readonly }"
                    />
                </div>

                <b-button
                    variant="secondary"
                    size="sm"
                    v-b-modal="modalId"
                    v-if="!field.readonly"
                >
                    {{ $t('fj.select_item', { model: field.title }) }}
                </b-button>
            </div>

            <slot />

            <fj-field-relation-modal
                :field="field"
                :model="model"
                :model-id="modelId"
                :hasMany="false"
                :selectedModels="selectedModels"
                @selected="selected"
            />
        </template>
        <template v-else>
            <fj-field-alert-not-created :field="field" class="mb-0" />
        </template>
    </fj-form-item>
</template>

<script>
import methods from '../methods';
import TableModel from '@fj-js/crud/table.model';
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
        modelId: {
            required: true
        }
    },
    data() {
        return {
            value: null,
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

        this.routePrefixes = { [this.selectedModel]: this.field.route_prefix };
        if ('route_prefixes' in this.field) {
            this.routePrefixes = this.field.routePrefixes;
        }
        this.setRelation();
    },

    methods: {
        ...methods,
        setRelation() {
            this.selectedModels = { [this.selectedModel]: [this.relation] };
            this.routePrefix = this.field.route_prefix;
            if ('route_prefixes' in this.field) {
                this.routePrefix = this.field.routePrefixes[this.selectedModel];
            }
        },
        async selected(item, modelName) {
            let response = null;
            switch (this.field.type) {
                case 'hasOne':
                case 'morphOne':
                case 'oneRelation':
                    try {
                        response = await axios.post(
                            `${this.field.route_prefix}/${this.field.id}/${item.id}`
                        );
                    } catch (e) {
                        console.log(e);
                        return;
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
                case 'morphTo':
                    this.model.attributes[this.field.morph_type] = modelName;
                    this.model.attributes[this.field.foreign_key] = item.id;
                    this.model[`${this.field.id}Model`] = item.attributes;
                    this.setCols();
                    this.$emit('changed', this.field, this.model);
                    break;
                case 'belongsTo':
                    this.setValue(item.id);
                    this.$emit('changed', item.id);
                    break;
            }
            this.relation = item;
            this.selectedModel = modelName;
            this.setRelation();

            this.$bvModal.hide(this.modalId);
        },
        async removeRelation({ id, modelName }) {
            let response = null;
            switch (this.field.type) {
                case 'hasOne':
                case 'morphOne':
                case 'oneRelation':
                    try {
                        response = axios.delete(
                            `${this.field.route_prefix}/${this.field.id}/${id}`
                        );
                    } catch (e) {
                        console.log(e);
                        return;
                    }
                    this.$bvToast.toast(this.$t('fj.relation_unlinked'), {
                        variant: 'success'
                    });
                    break;
                case 'belongsTo':
                    this.setValue(null);
                    this.$emit('changed', null);
                    break;
            }
            this.relation = null;
            this.setRelation();
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
