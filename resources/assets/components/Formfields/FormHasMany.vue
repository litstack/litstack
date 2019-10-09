<template>
    <fj-form-item :field="form_field" v-if="model.id">
        <b-card class="fjord-block no-fx mb-2">
            <div>
                <b-table-simple outlined table-variant="light">
                    <fj-colgroup :icons="['drag', 'controls']" :cols="fields" />

                    <draggable
                        v-model="relations"
                        @end="newOrder(relations)"
                        tag="tbody"
                        handle=".fjord-draggable__dragbar"
                    >
                        <b-tr v-for="(relation, rkey) in relations" :key="rkey">
                            <b-td
                                style="vertical-align: middle;"
                                v-for="(field, key) in fields"
                                :key="`td-${key}`"
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
                                                `${baseURL}${form_field.edit}/${
                                                    relation.id
                                                }/edit`
                                            "
                                            class="btn-transparent d-flex align-items-center"
                                            ><fa-icon icon="edit"
                                        /></b-button>
                                        <b-button
                                            class="btn-transparent"
                                            v-b-modal="
                                                `modal-${
                                                    model.attributes.id
                                                }-${key}`
                                            "
                                            ><fa-icon icon="trash"
                                        /></b-button>
                                    </b-button-group>
                                    <b-modal
                                        :id="
                                            `modal-${
                                                model.attributes.id
                                            }-${key}`
                                        "
                                        title="Delete Item"
                                    >
                                        Please confirm that you want to delete
                                        the item

                                        <template v-slot:modal-footer>
                                            <b-button
                                                variant="secondary"
                                                size="sm"
                                                class="float-right"
                                                @click="
                                                    $bvModal.hide(
                                                        `modal-${
                                                            model.attributes.id
                                                        }-${key}`
                                                    )
                                                "
                                            >
                                                cancel
                                            </b-button>
                                            <a
                                                href="#"
                                                @click.prevent="
                                                    removeRelation(relation.id)
                                                "
                                                class="fj-trash btn btn-danger btn-sm"
                                            >
                                                <fa-icon icon="trash" /> delete
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
                        </b-tr>
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
    </fj-form-item>
</template>

<script>
import TranslatableEloquent from './../../eloquent/translatable';
import TableModel from './../../eloquent/table.model';
import { mapGetters } from 'vuex';

export default {
    name: 'FormHasMany',
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
            } catch (e) {
                this.$notify({
                    group: 'general',
                    type: 'warning',
                    title: this.form_field.title,
                    text: e.response.data.message
                });
                return;
            }

            this.relations.push(item);

            this.$notify({
                group: 'general',
                type: 'success',
                title: this.form_field.title,
                text: `Added item to ${this.form_field.title}`
            });

            //this.$bvModal.hide(this.modalId)
        },
        async removeRelation(id, $event) {
            let relation = this.relations.find(r => r.id == id);
            let index = this.relations.indexOf(relation);

            this.relations.splice(index, 1);

            let payload = {
                from_model_type: this.model.model,
                from_model_id: this.model.id,
                to_model_type: this.form_field.model,
                to_model_id: id
            };

            await axios.post(`relations/delete`, payload);

            this.$notify({
                group: 'general',
                type: 'success',
                title: this.form_field.title,
                text: `Removed item from ${this.form_field.title}`
            });
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

            this.$notify({
                group: 'general',
                type: 'success',
                title: this.form_field.title,
                text: 'Changed order.'
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
        ...mapGetters(['baseURL'])
    }
};
</script>

<style lang="scss" scoped>
.fjord-draggable {
    margin-bottom: 5px;
}
.table-controls {
    margin: -24px 0;
    margin-right: -13px;
    height: 48px;
}
</style>
