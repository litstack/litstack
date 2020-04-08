<template>
    <fj-form-item :field="field" :model="model">
        <template v-if="model.id">
            <b-card class="fjord-block no-fx">
                <div v-if="model[`${field.id}Model`]">
                    <b-table-simple outlined table-variant="light">
                        <fj-colgroup :icons="['drag', 'trash']" :cols="cols" />

                        <tr>
                            <b-td
                                style="vertical-align: middle;"
                                v-for="(col, key) in cols"
                                :key="`td-${key}`"
                            >
                                <div
                                    v-if="col.key == 'trash'"
                                    class="text-center"
                                >
                                    <a
                                        href="#"
                                        @click.prevent="
                                            removeRelation(relation.id)
                                        "
                                        class="fj-trash text-muted"
                                    >
                                        <fa-icon icon="trash" />
                                    </a>
                                </div>
                                <div v-else>
                                    <fj-table-col :item="relation" :col="col" />
                                </div>
                            </b-td>
                        </tr>
                    </b-table-simple>
                </div>
                <div v-else>
                    <fj-form-relation-empty :field="field" />
                </div>

                <b-button variant="secondary" size="sm" v-b-modal="modalId">
                    Select {{ field.title }}
                </b-button>
            </b-card>

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
            <fj-form-relation-not-created :field="field" />
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
        }
    },
    data() {
        return {
            relation: {},
            cols: [],
            selectedModel: ''
        };
    },
    beforeMount() {
        this.setCols();
        let relation = this.model[`${this.field.id}`];

        if (relation) {
            this.relation = new TableModel(relation);
        }

        this.selectedModel = this.field.model;
        if (this.field.type == 'morphTo') {
            this.selectedModel = this.model[this.field.morph_type];
        }

        this.selectedModels = { [this.selectedModel]: [relation] };
    },
    methods: {
        setCols() {
            this.cols = [];
            let preview = this.field.preview;
            if (this.field.type == 'morphTo' && this.model[this.field.id]) {
                preview = this.field.preview[this.model[this.field.morph_type]];
            }
            for (let i = 0; i < preview.length; i++) {
                let col = preview[i];

                if (typeof col == typeof '') {
                    col = { key: col };
                }
                this.cols.push(col);
            }
            this.cols.push({ key: 'trash' });
        },
        async selected(item, modelName) {
            switch (this.field.type) {
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

            this.$bvModal.hide(this.modalId);
        },
        async removeRelation(item, modelName) {
            switch (this.field.type) {
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
        },
        setItem(item) {
            item.trash = '';
            return item;
        }
    },
    computed: {
        ...mapGetters(['form']),
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
