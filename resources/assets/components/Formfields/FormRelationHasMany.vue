<template>
    <fj-form-item :field="form_field">
        <template v-if="model.id">
            <b-card class="fjord-block no-fx mb-2">
                <div>
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
                                v-for="(relation, rkey) in relations"
                                :key="rkey"
                            >
                                <b-td
                                    style="vertical-align: middle;"
                                    v-for="(field, key) in fields"
                                    :key="`td-${key}`"
                                    class="position-relative"
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
                                        <fa-icon icon="grip-vertical" />
                                    </div>

                                    <div
                                        v-else-if="field.key == 'controls'"
                                        class="d-flex table-controls"
                                    >
                                        <b-button-group size="sm">
                                            <b-button
                                                v-if="hasEditLink(form_field)"
                                                :href="
                                                    `${baseURL}${
                                                        form_field.edit
                                                    }/${relation.id}/edit`
                                                "
                                                class="btn-transparent d-flex align-items-center"
                                                ><fa-icon icon="edit"
                                            /></b-button>
                                            <b-button
                                                class="btn-transparent"
                                                @click="
                                                    showModal(
                                                        `modal-${
                                                            form_field.edit
                                                        }-${relation.id}`
                                                    )
                                                "
                                                ><fa-icon icon="trash"
                                            /></b-button>
                                        </b-button-group>
                                        <b-modal
                                            :id="
                                                `modal-${form_field.edit}-${
                                                    relation.id
                                                }`
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
                                                            `modal-${
                                                                form_field.edit
                                                            }-${relation.id}`
                                                        )
                                                    "
                                                >
                                                    {{ $t('cancel') }}
                                                </b-button>
                                                <a
                                                    href="#"
                                                    @click.prevent="
                                                        removeRelation(
                                                            relation.id,
                                                            form_field.edit
                                                        )
                                                    "
                                                    class="fj-trash btn btn-danger btn-sm"
                                                >
                                                    <fa-icon icon="trash" />
                                                    {{ $t('delete') }}
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

                <b-button variant="secondary" size="sm" v-b-modal="modalId">
                    {{ form_field.button }}
                </b-button>
            </b-card>

            <slot />

            <fj-form-relation-modal
                :field="form_field"
                :model="model"
                :selectedModels="relations"
                @selected="selected"
                @remove="removeRelation"
            />
        </template>
        <template v-else>
            <b-alert show variant="warning"
                >{{ form.config.names.title.singular }} has to be created in
                order to add <i>{{ field.title }}</i></b-alert
            >
        </template>
    </fj-form-item>
</template>

<script>
import TranslatableEloquent from './../../eloquent/translatable';
import TableModel from './../../eloquent/table.model';
import { mapGetters } from 'vuex';

export default {
    name: 'FormRelationHasMany',
    props: {
        form_field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
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
            let payload = {
                from_model_type: this.model.model,
                from_model_id: this.model.id,
                to_model_type: this.form_field.model,
                to_model_id: item.id
            };

            try {
                await axios.post('relations/store', payload);

                let relation = this.form_field.title;
                this.$bvToast.toast(this.$t('relation_added', { relation }), {
                    variant: 'success'
                });
            } catch (e) {
                this.$bvToast.toast(e.response.data.message, {
                    variant: 'danger',
                    noAutoHide: true
                });
                return;
            }

            this.relations.push(item);
        },
        async removeRelation(id, $event) {
            let payload = {
                from_model_type: this.model.model,
                from_model_id: this.model.id,
                to_model_type: this.form_field.model,
                to_model_id: id
            };

            try {
                const { data } = await axios.post(`relations/delete`, payload);

                // close modal
                this.$bvModal.hide(`modal-${this.form_field.edit}-${id}`);

                let relation = this.relations.find(r => r.id == id);
                let index = this.relations.indexOf(relation);

                this.relations.splice(index, 1);

                this.$bvToast.toast(this.$t('relation_set'), {
                    variant: 'success'
                });
            } catch (e) {
                this.$bvToast.toast(e, {
                    variant: 'danger',
                    noAutoHide: true
                });
            }
        },
        async newOrder() {
            let ids = [];

            let relation_type = {
                from_model_type: this.model.model,
                from_model_id: this.model.id,
                to_model_type: this.form_field.model
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

            this.$bvToast.toast(this.$t('order_changed'), {
                variant: 'success'
            });
        },
        setFields() {
            this.fields.push({ key: 'drag' });
            for (let i = 0; i < this.form_field.preview.length; i++) {
                let field = this.form_field.preview[i];

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
        hasEditLink(form_field) {
            return form_field.edit != undefined;
        }
    },
    data() {
        return {
            relations: [],
            fields: []
        };
    },
    beforeMount() {
        this.setFields();

        let items = this.model[this.form_field.relationship] || [];

        for (let i = 0; i < items.length; i++) {
            this.addRelation(items[i]);
        }
    },
    computed: {
        modalId() {
            return `${this.model.route}-form-relation-table-${
                this.form_field.id
            }-${this.model.id}`;
        },
        ...mapGetters(['baseURL', 'form'])
    }
};
</script>

<style lang="scss" scoped>
.fjord-draggable {
    margin-bottom: 5px;
}
.table-controls {
    // margin: -24px 0;
    // margin-right: -13px;
    // height: 48px;
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
