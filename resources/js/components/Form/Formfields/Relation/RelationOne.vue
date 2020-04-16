<template>
    <fj-form-item :field="field" :model="model" v-bind:no-hint="!readonly">
        <template v-if="model.id">
            <div class="form-control-expand">
                <div v-if="model[`${field.id}Model`]">
                    <b-table-simple
                        outlined
                        table-variant="light"
                        :class="{ 'mb-0': readonly }"
                    >
                        <fj-base-colgroup
                            :icons="['drag', 'controls']"
                            :cols="cols"
                        />

                        <tr>
                            <b-td
                                style="vertical-align: middle;"
                                class="position-relative"
                                v-for="(col, key) in cols"
                                :key="`td-${key}`"
                            >
                                <div
                                    v-if="col.value == 'controls'"
                                    class="d-flex table-controls"
                                >
                                    <b-button-group size="sm">
                                        <b-button
                                            :href="
                                                `${baseURL}${field.route}/${relation.id}/edit`
                                            "
                                            class="btn-transparent d-flex align-items-center"
                                        >
                                            <fa-icon icon="eye" />
                                        </b-button>
                                        <b-button
                                            v-if="!readonly"
                                            class="btn-transparent"
                                            @click="
                                                field.confirm_unlink
                                                    ? showModal(
                                                          `modal-${route}-${relation.id}`
                                                      )
                                                    : removeRelation(
                                                          relation.id,
                                                          relation
                                                      )
                                            "
                                        >
                                            <fa-icon icon="unlink" />
                                        </b-button>
                                        <b-modal
                                            :id="
                                                `modal-${route}-${relation.id}`
                                            "
                                            title="Unlink Item"
                                        >
                                            {{ $t('fj.confirm_unlink') }}

                                            <template v-slot:modal-footer>
                                                <b-button
                                                    variant="secondary"
                                                    size="sm"
                                                    class="float-right"
                                                    @click="
                                                        $bvModal.hide(
                                                            `modal-${route}-${relation.id}`
                                                        )
                                                    "
                                                >
                                                    {{
                                                        $t(
                                                            'fj.cancel'
                                                        ).capitalize()
                                                    }}
                                                </b-button>
                                                <a
                                                    href="#"
                                                    @click.prevent="
                                                        removeRelation(
                                                            relation.id
                                                        )
                                                    "
                                                    class="fj-trash btn btn-danger btn-sm"
                                                >
                                                    <fa-icon icon="unlink" />
                                                    {{
                                                        $t(
                                                            'fj.delete'
                                                        ).capitalize()
                                                    }}
                                                </a>
                                            </template>
                                        </b-modal>
                                    </b-button-group>
                                </div>
                                <div v-else>
                                    <fj-table-col :item="relation" :col="col" />
                                </div>
                            </b-td>
                        </tr>
                    </b-table-simple>
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
        this.route = this.field.route;
        if ('routes' in this.field) {
            this.route = this.field.routes[this.selectedModel];
        }
    },
    methods: {
        showModal(id) {
            this.$bvModal.show(id);
        },
        setCols() {
            this.cols = [];
            let preview = this.field.preview;
            if (this.field.type == 'morphTo' && this.model[this.field.id]) {
                preview = this.field.preview[this.model[this.field.morph_type]];
            }
            for (let i = 0; i < preview.length; i++) {
                let col = preview[i];

                if (typeof col == typeof '') {
                    col = { value: col };
                }
                this.cols.push(col);
            }
            this.cols.push({ value: 'controls' });
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
            if ('routes' in this.field) {
                this.route = this.field.routes[this.selectedModel];
            }
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
