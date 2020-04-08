<template>
    <fj-form-item :field="field">
        <template v-if="model.id">
            <b-card class="fjord-block no-fx">
                <div v-if="!!relations.length">
                    <b-table-simple outlined table-variant="light">
                        <fj-colgroup
                            :icons="['drag', 'controls']"
                            :cols="fields"
                        />

                        <draggable
                            v-model="relations"
                            @end="newOrder(relations)"
                            tag="tbody"
                            handle=".fjord-draggable__dragbar"
                        >
                            <tr
                                class="draggable-tr"
                                v-for="(relation, rkey) in relations"
                                :key="rkey"
                            >
                                <b-td
                                    style="vertical-align: middle;"
                                    v-for="(field, key) in fields"
                                    :key="`td-${key}`"
                                    class="position-relative"
                                    :test="field.key"
                                    v-if="
                                        !(field.key == 'drag' && field.readonly)
                                    "
                                    :class="
                                        field.key == 'drag'
                                            ? 'fjord-draggable__dragbar'
                                            : ''
                                    "
                                >
                                    <div
                                        v-if="field.key == 'drag'"
                                        class="text-center text-muted"
                                    >
                                        <fa-icon
                                            icon="grip-vertical"
                                            v-if="!field.readonly"
                                        />
                                    </div>

                                    <div
                                        v-else-if="field.key == 'controls'"
                                        class="d-flex table-controls"
                                    >
                                        <b-button-group size="sm">
                                            <b-button
                                                v-if="
                                                    hasEditLink(field) &&
                                                        (can(
                                                            `update ${field.edit}`
                                                        ) ||
                                                            can(
                                                                `read ${field.edit}`
                                                            ))
                                                "
                                                :href="
                                                    `${baseURL}${field.edit}/${relation.id}/edit`
                                                "
                                                class="btn-transparent d-flex align-items-center"
                                            >
                                                <fa-icon
                                                    :icon="
                                                        can(
                                                            `update ${field.edit}`
                                                        )
                                                            ? 'edit'
                                                            : 'eye'
                                                    "
                                                />
                                            </b-button>
                                            <b-button
                                                v-if="!field.readonly"
                                                class="btn-transparent"
                                                @click="
                                                    showModal(
                                                        `modal-${field.edit}-${relation.id}`
                                                    )
                                                "
                                            >
                                                <fa-icon icon="trash" />
                                            </b-button>
                                        </b-button-group>
                                        <b-modal
                                            :id="
                                                `modal-${field.edit}-${relation.id}`
                                            "
                                            title="Delete Item"
                                        >
                                            Please confirm that you want to
                                            delete the item

                                            <template v-slot:modal-footer>
                                                <b-button
                                                    variant="secondary"
                                                    size="sm"
                                                    class="float-right"
                                                    @click="
                                                        $bvModal.hide(
                                                            `modal-${field.edit}-${relation.id}`
                                                        )
                                                    "
                                                >
                                                    {{ $t('fj.cancel') }}
                                                </b-button>
                                                <a
                                                    href="#"
                                                    @click.prevent="
                                                        removeRelation(
                                                            relation.id,
                                                            field.edit
                                                        )
                                                    "
                                                    class="fj-trash btn btn-danger btn-sm"
                                                >
                                                    <fa-icon icon="trash" />
                                                    {{ $t('fj.delete') }}
                                                </a>
                                            </template>
                                        </b-modal>
                                    </div>
                                    <div v-else>
                                        <fj-table-col
                                            :item="relation"
                                            :col="field"
                                        />
                                    </div>
                                </b-td>
                            </tr>
                        </draggable>
                    </b-table-simple>
                </div>
                <div v-else>
                    <fj-form-relation-empty :field="field" />
                </div>

                <b-button
                    variant="secondary"
                    size="sm"
                    v-b-modal="modalId"
                    v-if="!field.readonly"
                >
                    Add {{ field.id }}
                </b-button>
            </b-card>

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
            <fj-form-relation-not-created :field="field" />
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
        type: {
            required: true,
            type: String
        }
    },
    data() {
        return {
            selectedItems: {},
            relations: [],
            fields: []
        };
    },
    beforeMount() {
        this.setFields();

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
                switch (this.type) {
                    case 'hasMany':
                        response = axios.put(`${this.field.route}/${item.id}`, {
                            [this.field.foreign_key]: this.model.id
                        });
                        break;
                    case 'morphMany':
                        response = axios.put(`${this.field.route}/${item.id}`, {
                            [this.field.morph_type]: this.field
                                .morph_type_value,
                            [this.field.foreign_key]: this.model.id
                        });
                        break;
                    case 'morphedByMany':
                    case 'morphToMany':
                    case 'belongsToMany':
                    case 'relation':
                        response = await axios.post(
                            `${this.form.config.route}/${this.model.id}/relation/${this.field.id}/${item.id}`
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
        async removeRelation(id, $event) {
            let response = null;
            // TODO: create resource crud/relation for create delete

            try {
                switch (this.type) {
                    case 'hasMany':
                        response = await axios.put(
                            `${this.field.route}/${id}`,
                            {
                                [this.field.foreign_key]: null
                            }
                        );
                        break;
                    case 'morphMany':
                        response = axios.put(`${this.field.route}/${id}`, {
                            [this.field.morph_type]: null,
                            [this.field.foreign_key]: null
                        });
                        break;
                    case 'morphedByMany':
                    case 'morphToMany':
                    case 'belongsToMany':
                    case 'relation':
                        response = await axios.delete(
                            `${this.form.config.route}/${this.model.id}/relation/${this.field.id}/${id}`
                        );
                        break;
                }
            } catch (e) {
                console.log(e);
                return;
            }
            // close modal
            this.$bvModal.hide(`modal-${this.field.edit}-${id}`);

            let relation = this.relations.find(r => r.id == id);
            let index = this.relations.indexOf(relation);

            this.relations.splice(index, 1);

            this.$bvToast.toast(this.$t('fj.relation_set'), {
                variant: 'success'
            });
        },
        async newOrder() {
            let ids = [];

            let relation_type = {
                from_model_type: this.model.model,
                from_model_id: this.model.id,
                to_model_type: this.field.model
            };
            for (let i = 0; i < this.relations.length; i++) {
                let relation = this.relations[i];
                ids.push(relation.id);
            }

            let payload = {
                data: relation_type,
                ids
            };

            let response = await axios.put('relations/order', payload);

            this.$bvToast.toast(this.$t('fj.order_changed'), {
                variant: 'success'
            });
        },
        setFields() {
            if (!this.readonly && this.field.type == 'relation') {
                this.fields.push({ key: 'drag' });
            }

            for (let i = 0; i < this.field.preview.length; i++) {
                let field = this.field.preview[i];

                if (typeof field == typeof '') {
                    field = { key: field };
                }
                this.fields.push(field);
            }
            this.fields.push({ key: 'controls' });
        },
        colSize(field) {
            if (field.key == 'trash' || field.key == 'drag') {
                return '10px';
            }

            if (field.type == 'image') {
                return '40px';
            }

            return '100%';
        },
        hasEditLink(field) {
            return field.edit != undefined;
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
