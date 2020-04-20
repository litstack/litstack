<template>
    <fj-form-item :field="field" v-bind:no-hint="!readonly">
        <template v-if="model.id">
            <div class="form-control-expand">
                <div v-if="!!relations.length">
                    <fj-form-relation-index
                        :model="model"
                        :field="field"
                        :items="{ [field.model]: relations }"
                        :readonly="readonly"
                        :routePrefixes="{ [field.model]: field.route_prefix }"
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
                    v-if="!field.readonly"
                >
                    Add {{ field.id }}
                </b-button>
            </div>

            <slot />

            <fj-form-relation-modal
                v-if="!field.readonly"
                :field="field"
                :model="model"
                :selectedModels="{ [field.model]: relations }"
                @selected="selected"
                @remove="removeRelation"
            />
        </template>
        <template v-else>
            <fj-form-alert-not-created :field="field" class="mb-0" />
        </template>
    </fj-form-item>
</template>

<script>
import TableModel from '@fj-js/eloquent/table.model';
import { mapGetters } from 'vuex';

export default {
    name: 'FormRelationMany',
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
            selectedItems: {},
            relations: [],
            cols: []
        };
    },
    beforeMount() {
        this.setCols();

        let items = this.model[this.field.id] || [];

        for (let i = 0; i < items.length; i++) {
            this.addRelation(items[i]);
        }
    },
    methods: {
        showModal(id) {
            this.$bvModal.show(id);
        },
        async addRelation(item) {
            this.relations.push(new TableModel(item));
        },
        async selected(item) {
            let response = null;
            try {
                switch (this.field.type) {
                    case 'hasMany':
                        response = axios.put(
                            `${this.field.route_prefix}/${item.id}`,
                            {
                                [this.field.foreign_key]: this.model.id
                            }
                        );
                        break;
                    case 'morphMany':
                        response = axios.put(
                            `${this.field.route_prefix}/${item.id}`,
                            {
                                [this.field.morph_type]: this.field
                                    .morph_type_value,
                                [this.field.foreign_key]: this.model.id
                            }
                        );
                        break;
                    case 'morphedByMany':
                    case 'morphToMany':
                    case 'belongsToMany':
                    case 'manyRelation':
                        response = await axios.post(
                            `${this.form.config.route_prefix}/${this.model.id}/${this.field.id}/${item.id}`
                        );
                        break;
                }
            } catch (e) {
                console.log(e);
                return;
            }
            let relation = this.field.title;
            this.$bvToast.toast(this.$t('fj.relation_added', { relation }), {
                variant: 'success'
            });

            this.relations.push(item);
        },
        async removeRelation({ id }) {
            let response = null;
            // TODO: create resource crud/relation for create delete
            try {
                switch (this.field.type) {
                    case 'hasMany':
                        response = await axios.put(
                            `${this.field.route_prefix}/${id}`,
                            {
                                [this.field.foreign_key]: null
                            }
                        );
                        break;
                    case 'morphMany':
                        response = axios.put(
                            `${this.field.route_prefix}/${id}`,
                            {
                                [this.field.morph_type]: 'null',
                                [this.field.foreign_key]: 0
                            }
                        );
                        break;
                    case 'morphedByMany':
                    case 'morphToMany':
                    case 'belongsToMany':
                    case 'manyRelation':
                    case 'relation':
                        response = await axios.delete(
                            `${this.form.config.route_prefix}/${this.model.id}/${this.field.id}/${id}`
                        );
                        break;
                }
            } catch (e) {
                console.log(e);
                return;
            }
            // close modal
            this.$bvModal.hide(`modal-${this.field.route}-${id}`);

            let relation = this.relations.find(r => r.id == id);
            let index = this.relations.indexOf(relation);

            this.relations.splice(index, 1);

            this.$bvToast.toast(this.$t('fj.relation_unlinked'), {
                variant: 'success'
            });
        },
        setCols() {
            if (!this.readonly && this.field.sortable) {
                this.cols.push({ value: 'drag' });
            }

            for (let i = 0; i < this.field.preview.length; i++) {
                let col = this.field.preview[i];

                if (typeof col == typeof '') {
                    col = { value: col };
                }
                this.cols.push(col);
            }
            this.cols.push({ value: 'controls' });
        },
        colSize(field) {
            if (field.value == 'trash' || field.value == 'drag') {
                return '10px';
            }

            if (field.type == 'image') {
                return '40px';
            }

            return '100%';
        }
    },

    computed: {
        modalId() {
            return `${this.model.route}-form-relation-table-${this.field.id}-${this.model.id}`;
        },
        ...mapGetters(['baseURL', 'form'])
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
