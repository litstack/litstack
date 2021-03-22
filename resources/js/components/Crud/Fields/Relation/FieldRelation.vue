<template>
    <lit-base-field :field="field" :model="model" class="">
        <template slot="title-right">
            <b-button
                variant="secondary"
                size="sm"
                v-b-modal="field.creation_form ? creationModalId : modalId"
                v-if="!field.readonly && !create"
            >
                <span v-if="field.add_button_text" v-html="field.add_button_text"/>
                <template v-else>
                    <lit-fa-icon :icon="field.many ? 'plus' : 'link'" />
                    {{
                        field.many
                            ? __('base.item_add', {
                                item: field.names.singular,
                            })
                            : __('base.item_select', {
                                item: field.names.singular,
                            })
                    }}
                    <lit-field-relation-form
                        :modal-id="creationModalId"
                        :field="field"
                        :model="model"
                        :form="field.creation_form"
                        @update="$emit('update')"
                    />
                </template>
            </b-button>
        </template>

        <template v-if="model.id">
            <div
                class="form-control-expand lit-field-relation"
                :class="{ 'mt-2': !showTableHead }"
                v-if="field.previewType == 'table'"
            >
                <lit-index-table
                    ref="table"
                    :cols="field.preview"
                    :items="selectedRelations"
                    :load-items="loadRelations"
                    no-card
                    no-select
                    v-bind:no-head="!showTableHead"
                    :sort-by-default="
                        field.sortable
                            ? `${field.orderColumn}.${field.orderDirection}`
                            : null
                    "
                    :name-singular="field.names.singular"
                    :name-plural="field.names.plural"
                    :searchKeys="field.searchable ? field.search : []"
                    :per-page="field.perPage"
                    v-bind:small="field.small"
                    :sortable="field.sortable ? 'force' : false"
                    @sorted="newOrder"
                    @unlink="removeRelation"
                    :model="model"
                    :field="field"
                />
            </div>
            <div
                v-else-if="field.previewType == 'tags'"
                class="lit-field-relation-tags mt-2"
                :class="{
                    'lit-field-relation-tags-small': field.small,
                    'pt-0': !field.small,
                    'mt-1': field.small,
                }"
            >
                <div
                    class="mt-3 text-center text-secondary"
                    v-if="busy && _.isEmpty(selectedRelations)"
                >
                    <lit-fa-icon icon="circle-notch" spin />
                </div>
                <div
                    v-if="!busy && _.isEmpty(selectedRelations)"
                    class="mt-3 text-center text-secondary"
                >
                    <lit-fa-icon icon="tags" />
                </div>
                <b-form-tag
                    v-for="(relation, key) in selectedRelations"
                    :key="key"
                    @remove="removeRelation(relation)"
                    :class="{
                        'relation-tag-sm': field.small,
                        'mr-1': field.small,
                        'mt-1': field.small,
                        'mt-3': !field.small,
                        'mr-3': !field.small,
                    }"
                    tag-pills
                    :variant="field.tagVariant"
                >
                    <span v-html="_format(field.tagValue, relation)" />
                </b-form-tag>
            </div>
            <template v-else-if="field.previewType == 'link'">
                <b-overlay
                    :show="busy"
                    rounded="md"
                    opacity="0.6"
                    spinner-variant="primary"
                    spinner-type="grow"
                    class="w-100 mt-2"
                >
                    <div
                        class="lit-field-relation-link form-control d-flex justify-content-between br-2"
                    >
                        <template
                            v-if="selectedRelations.length > 0 && busy == false"
                        >
                            <component
                                :is="field.related_route_prefix ? 'a' : 'span'"
                                :href="relatedLink(selectedRelations[0])"
                                v-html="
                                    _format(
                                        field.linkValue,
                                        selectedRelations[0]
                                    )
                                "
                            />
                            <div v-if="!field.readonly">
                                <lit-field-relation-col-unlink
                                    :item="selectedRelations[0]"
                                    @unlink="removeRelation"
                                />
                            </div>
                        </template>
                    </div>
                </b-overlay>
            </template>

            <lit-field-relation-confirm-delete
                v-for="(relation, index) in selectedRelations"
                :key="index"
                :field="field"
                :relation="relation"
                :route-prefix="field.route_prefix"
                @confirmed="_removeRelation"
                @canceled="$refs.modal.$emit('refresh')"
            />
            <lit-field-relation-modal
                ref="modal"
                :field="field"
                :model="model"
                :cols="modalCols"
                :modal-id="modalId"
                :selectedRelations="allSelectedRelations"
                @select="selectRelation"
                @remove="removeRelation"
            />
        </template>

        <template v-else>
            <lit-field-alert-not-created :field="field" class="mb-0" />
        </template>
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldRelation',
    props: {
        field: {
            type: Object,
        },
        model: {
            type: Object,
        },
        modelId: {
            required: true,
        },
    },
    data() {
        return {
            /**
             * All selected relation.
             */
            allSelectedRelations: [],

            /**
             * Visible selected relations.
             */
            selectedRelations: [],

            /**
             * Busy state.
             */
            busy: true,

            /**
             * Table cols.
             */
            cols: [],

            /**
             * Table cols in modal.
             */
            modalCols: [],
        };
    },
    computed: {
        /**
         * Unique modal id.
         *
         * @return {String}
         */
        modalId() {
            return `form-relation-table-${this.field.id}-${
                this.model.id
            }-${this.field.route_prefix.replace(/\//g, '-')}`;
        },

        /**
         * Unique modal id for creation form modal.
         *
         * @return {String}
         */
        creationModalId() {
            return `form-relation-creation-${this.field.id}-${
                this.model.id
            }-${this.field.route_prefix.replace(/\//g, '-')}`;
        },

        /**
         * Show table head.
         *
         * @return {Boolean}
         */
        showTableHead() {
            if (!this.field.many) {
                return false;
            }
            return this.field.showTableHead === true;
        },

        /**
         * Is on create page.
         *
         * @return {Boolean}
         */
        create() {
            return this.model.id === undefined;
        },
    },
    beforeMount() {
        this.cols = this.field.preview;
        this.modalCols = Lit.clone(this.field.preview);
        if (this.field.related_route_prefix && !this.field.hide_relation_link) {
            this.cols.push({
                label: '',
                name: 'lit-field-relation-col-link',
                small: true,
            });
        }
        if (!this.field.readonly) {
            this.cols.push({
                label: '',
                name: 'lit-field-relation-col-unlink',
                small: true,
            });
        }
        if (
            !_.isEmpty(this.field.update_form) &&
            this.field.update_form.allow.update
        ) {
            this.cols.push({
                label: '',
                name: 'lit-field-relation-col-edit',
                props: {
                    field: this.field,
                },
                small: true,
            });
        }
        this.loadAllRelations();
    },
    methods: {
        /**
         * Related edit link.
         *
         * @param {Object} relation
         * @return {String}
         */
        relatedLink(relation) {
            return `${Lit.baseURL}${this.field.related_route_prefix}/${relation.id}`;
        },

        /**
         * Load all relations.
         *
         * @return {undefined}
         */
        async loadAllRelations() {
            if (this.create) {
                return;
            }
            let payload = {
                perPage: 999999,
                page: 1,
                sort_by: 'id.desc',
            };
            let response = await this.sendLoadRelations(payload);

            if (!response) {
                return;
            }

            for (let i in response.data.items) {
                let relation = response.data.items[i];
                this.allSelectedRelations.push({
                    id: relation.attributes.id,
                });
            }

            if (this.field.previewType != 'table') {
                this.loadRelations({
                    perPage: 999999,
                    page: 1,
                    sort_by: 'id.desc',
                });
            }
        },

        /**
         * Load relations from payload.
         *
         * @param {Object} payload
         * @return {undefined}
         */
        async loadRelations(payload) {
            if (this.create) {
                return;
            }
            this.busy = true;

            let response = await this.sendLoadRelations(payload);

            if (!response) {
                return;
            }

            this.selectedRelations = [];
            for (let i in response.data.items) {
                let block = response.data.items[i];
                this.newRelation(block);
            }
            this.busy = false;
            return response;
        },

        /**
         * Send load relation request.
         */
        async sendLoadRelations(payload) {
            try {
                return await axios.post(
                    `${this.field.route_prefix}/relation/load`,
                    {
                        field_id: this.field.id,
                        ...this.field.params,
                        ...payload,
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },

        /**
         * Add new relation.
         *
         * @param {Object} relation
         * @return {undefined}
         */
        newRelation(relation) {
            this.selectedRelations.push(this.crud(relation));
        },

        /**
         * Select relation.
         *
         * @param {Object} relation
         * @return {undefined}
         */
        async selectRelation(relation) {
            let response = await this.sendSelectRelation(relation);

            if (!response) {
                return;
            }

            this.$bvToast.toast(
                this.__('crud.fields.relation.messages.relation_linked', {
                    relation: this.field.title,
                }),
                {
                    variant: 'success',
                }
            );

            if (!this.field.many) {
                this.allSelectedRelations = [];
                this.allSelectedRelations.push(relation);

                this.$bvModal.hide(this.modalId);
            } else {
                this.allSelectedRelations.push(relation);
            }
            if (this.field.previewType != 'table') {
                this.loadRelations();
            } else {
                this.$refs.table.$emit('reload');
            }
            this.$emit('reload', relation);
            Lit.bus.$emit('field:updated', 'relation:selected');
        },

        /**
         * Send select relation request.
         */
        async sendSelectRelation(relation) {
            try {
                return await axios.post(
                    `${
                        this.field.route_prefix
                    }/${this.field.type.slugify()}/create`,
                    {
                        field_id: this.field.id,
                        related_id: relation.id,
                        ...this.field.params,
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },

        /**
         * Open confirm delete modal. Or delete relation directly.
         *
         * @param {Object} relation
         * @return {undefined}
         */
        removeRelation(relation) {
            if (!this.field.confirm) {
                return this._removeRelation(relation);
            }

            this.$bvModal.show(`modal-${this.field.id}-${relation.id}`);
        },

        /**
         * Delete relation.
         *
         * @param {Object} relation
         * @return {undefined}
         */
        async _removeRelation(relation) {
            let response = await this.sendDestroyRelation(relation);

            if (!response) {
                return;
            }

            this.$bvToast.toast(
                this.__('crud.fields.relation.messages.relation_unlinked'),
                {
                    variant: 'success',
                }
            );

            if (this.field.many) {
                for (let i in this.allSelectedRelations) {
                    if (this.allSelectedRelations[i].id == relation.id) {
                        this.allSelectedRelations.splice(i, 1);
                    }
                }
            } else {
                this.allSelectedRelations = [];
            }
            if (this.field.previewType != 'table') {
                this.loadRelations();
            } else {
                this.$refs.table.$emit('reload');
            }
            this.$emit('reload');

            Lit.bus.$emit('field:updated', 'relation:removed');
        },

        /**
         * Send remove relation request.
         */
        async sendDestroyRelation(relation) {
            try {
                return await axios.post(
                    `${
                        this.field.route_prefix
                    }/${this.field.type.slugify()}/destroy`,
                    {
                        field_id: this.field.id,
                        related_id: relation.id,
                        ...this.field.params,
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },

        /**
         * Order relations.
         */
        async newOrder({ ids, sortedItems }) {
            this.selectedRelations = sortedItems;
            let payload = {
                ids,
            };

            let response = await this.sendNewOrder(payload);

            if (!response) {
                return;
            }

            this.$emit('reload');
            Lit.bus.$emit('field:updated', 'relation:ordered');

            this.$bvToast.toast(this.__('base.messages.order_changed'), {
                variant: 'success',
            });
        },

        /**
         * Send new order request.
         */
        async sendNewOrder(payload) {
            try {
                return await axios.put(
                    `${this.field.route_prefix}/relation/order`,
                    {
                        field_id: this.field.id,
                        ...this.field.params,
                        ...payload,
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
.lit-field-relation {
    margin-left: -$card-spacer-x;
    margin-right: -$card-spacer-x;

    .lit-index-table {
        background-color: transparent;
    }

    &-tags {
        border: $input-border-width solid $input-border-color;
        border-radius: $input-border-radius;
        padding: map-get($spacers, 3);
        width: 100%;

        &-small {
            padding: 0;
            border: none;
            margin-bottom: 1rem;
        }

        .relation-tag-sm {
            padding: 0 0.5rem;
            font-size: 0.75rem;
        }
    }

    &-link {
        a {
            font-weight: 600;
        }
    }

    @media (max-width: map-get($grid-breakpoints, $nav-breakpoint-mobile)) {
        margin-left: 0;
        margin-right: 0;
        .lit-index-table {
            .card-body {
                padding: 0;
            }
        }
    }
}
</style>
