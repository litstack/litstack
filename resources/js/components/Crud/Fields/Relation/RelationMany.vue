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
                    <!--
                    <b-table-simple
                        outlined
                        hover
                        :class="{ 'mb-0': readonly }"
                    >
                        <fj-base-colgroup
                            :icons="['drag', 'controls']"
                            :cols="cols"
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
                                    v-for="(col, key) in cols"
                                    :key="`td-${key}`"
                                    class="position-relative"
                                    :test="col.value"
                                    v-if="
                                        !(col.value == 'drag' && field.readonly)
                                    "
                                    :class="
                                        col.value == 'drag'
                                            ? 'fjord-draggable__dragbar'
                                            : ''
                                    "
                                >
                                    <div
                                        v-if="col.value == 'drag'"
                                        class="text-center text-muted"
                                    >
                                        <fa-icon
                                            icon="grip-vertical"
                                            v-if="!field.readonly"
                                        />
                                    </div>

                                    <div
                                        v-else-if="col.value == 'controls'"
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
                                                              `modal-${field.route}-${relation.id}`
                                                          )
                                                        : removeRelation(
                                                              relation.id
                                                          )
                                                "
                                            >
                                                <fa-icon icon="unlink" />
                                            </b-button>
                                        </b-button-group>
                                        <b-modal
                                            :id="
                                                `modal-${field.route}-${relation.id}`
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
                                                            `modal-${field.route}-${relation.id}`
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
                                                            relation.id,
                                                            field.route
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
                                    </div>
                                    <div v-else>
                                        <fj-table-col
                                            :item="relation"
                                            :col="col"
                                        />
                                    </div>
                                </b-td>
                            </tr>
                        </draggable>
                    </b-table-simple>
                    -->
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
