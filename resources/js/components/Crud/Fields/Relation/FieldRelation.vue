<template>
    <fj-base-field :field="field" :model="model" class="">
        <template slot="title-right">
            <b-button
                variant="secondary"
                size="sm"
                v-b-modal="modalId"
                v-if="!field.readonly && !this.create"
            >
                <fa-icon :icon="field.many ? 'plus' : 'link'" />
                {{
                    field.many
                        ? __('fj.add_model', {
                              model: field.names.singular
                          })
                        : __('fj.select_item', {
                              item: field.names.singular
                          })
                }}
            </b-button>
        </template>

        <template v-if="model.id">
            <div
                class="form-control-expand fj-field-relation"
                :class="{ 'mt-2': !showTableHead }"
                v-if="field.previewType == 'table'"
            >
                <fj-index-table
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
                />
            </div>
            <div
                v-else-if="field.previewType == 'tags'"
                class="fj-field-relation-tags mt-2"
            >
                <div
                    class="mt-3 text-center text-secondary"
                    v-if="busy && _.isEmpty(selectedRelations)"
                >
                    <fa-icon icon="circle-notch" spin />
                </div>
                <div
                    v-if="!busy && _.isEmpty(selectedRelations)"
                    class="mt-3 text-center text-secondary"
                >
                    <fa-icon icon="tags" />
                </div>
                <b-form-tag
                    v-for="(relation, key) in selectedRelations"
                    :key="key"
                    @remove="removeRelation(relation)"
                    class="mr-3 mt-3"
                    :variant="field.tagVariant"
                >
                    <span v-html="_format(field.tagValue, relation)" />
                </b-form-tag>
            </div>
            <div
                v-else-if="field.previewType == 'link'"
                class="fj-field-relation-link"
            >
                <component
                    :is="field.related_route_prefix ? 'a' : 'span'"
                    :href="relatedLink(selectedRelations[0])"
                    v-if="selectedRelations.length > 0 && busy == false"
                    v-html="_format(field.linkValue, selectedRelations[0])"
                />
            </div>

            <fj-field-relation-confirm-delete
                v-for="(relation, index) in selectedRelations"
                :key="index"
                :field="field"
                :relation="relation"
                :route-prefix="field.route_prefix"
                @confirmed="_removeRelation"
                @canceled="$refs.modal.$emit('refresh')"
            />
            <fj-field-relation-modal
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
            <fj-field-alert-not-created :field="field" class="mb-0" />
        </template>
    </fj-base-field>
</template>

<script>
export default {
    name: 'FieldRelation',
    props: {
        field: {
            type: Object
        },
        model: {
            type: Object
        },
        modelId: {
            required: true
        }
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
            modalCols: []
        };
    },
    computed: {
        /**
         * Unique modal id.
         *
         * @return {String}
         */
        modalId() {
            return `form-relation-table-${
                this.field.id
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
        }
    },
    beforeMount() {
        this.cols = this.field.preview;
        this.modalCols = Fjord.clone(this.field.preview);
        this.cols.push({
            label: '',
            component: 'fj-field-relation-col-link',
            props: {
                field: this.field
            },
            small: true
        });
        if (!this.field.readonly) {
            this.cols.push({
                label: '',
                component: 'fj-field-relation-col-unlink',
                small: true
            });
        }
        if (!_.isEmpty(this.field.form)) {
            this.cols.push({
                label: '',
                component: 'fj-field-relation-col-edit',
                props: {
                    field: this.field
                },
                small: true
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
            return `${Fjord.baseURL}${this.field.related_route_prefix}/${relation.id}`;
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
                sort_by: 'id.desc'
            };
            let response = await axios.post(
                `${this.field.route_prefix}/${this.field.id}`,
                payload
            );
            for (let i in response.data.items) {
                let relation = response.data.items[i];
                this.allSelectedRelations.push({
                    id: relation.attributes.id
                });
            }

            if (this.field.previewType != 'table') {
                this.loadRelations({
                    perPage: 999999,
                    page: 1,
                    sort_by: 'id.desc'
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
            let response = await axios.post(
                `${this.field.route_prefix}/${this.field.id}`,
                payload
            );
            this.selectedRelations = [];
            for (let i in response.data.items) {
                let block = response.data.items[i];
                this.newRelation(block);
            }
            this.busy = false;
            return response;
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
            let response = null;
            switch (this.field.type) {
                case 'morphMany':
                case 'hasMany':
                case 'morphedByMany':
                case 'morphToMany':
                case 'belongsToMany':
                case 'manyRelation':
                    try {
                        response = await axios.post(
                            `${this.field.route_prefix}/${this.field.id}/${relation.id}`
                        );
                    } catch (e) {
                        console.log(e);
                        return;
                    }
                    this.$bvToast.toast(
                        this.$t('fj.relation_added', {
                            relation: this.field.names.singular
                        }),
                        {
                            variant: 'success'
                        }
                    );
                    break;
                case 'hasOne':
                case 'morphOne':
                case 'morphTo':
                case 'oneRelation':
                    try {
                        response = await axios.post(
                            `${this.field.route_prefix}/${this.field.id}/${relation.id}`
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
                case 'belongsTo':
                    try {
                        response = await axios.put(
                            `${this.field.route_prefix}/`,
                            {
                                [this.field.local_key] : relation.id
                            }
                        );
                    } catch (e) {
                        console.log(e);
                        return;
                    }

                    this.$emit('input', relation.id);

                    this.$bvToast.toast(
                        this.$t('fj.relation_added', {
                            relation: this.field.names.singular
                        }),
                        {
                            variant: 'success'
                        }
                    );
            }

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
            Fjord.bus.$emit('field:updated', 'relation:selected');
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
            let response = null;
            switch (this.field.type) {
                case 'morphMany':
                case 'hasMany':
                case 'morphedByMany':
                case 'morphToMany':
                case 'belongsToMany':
                case 'manyRelation':
                case 'relation':
                    try {
                        response = await axios.delete(
                            `${this.field.route_prefix}/${this.field.id}/${relation.id}`
                        );
                    } catch (e) {
                        console.log(e);
                        return;
                    }
                    this.$bvToast.toast(this.$t('fj.relation_unlinked'), {
                        variant: 'success'
                    });
                    break;
                case 'hasOne':
                case 'morphOne':
                case 'morphTo':
                case 'oneRelation':
                    try {
                        response = axios.delete(
                            `${this.field.route_prefix}/${this.field.id}/${relation.id}`
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
                    this.$emit('input', null);
                    break;
            }

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

            Fjord.bus.$emit('field:updated', 'relation:removed');
        },

        /**
         * Order relations.
         */
        async newOrder({ ids, sortedItems }) {
            this.selectedRelations = sortedItems;
            let payload = {
                ids
            };
            try {
                let response = await axios.put(
                    `${this.field.route_prefix}/${this.field.id}/order`,
                    payload
                );
            } catch (e) {
                console.log(e);
                return;
            }

            this.$emit('reload');
            Fjord.bus.$emit('field:updated', 'relation:ordered');

            this.$bvToast.toast(this.$t('fj.order_changed'), {
                variant: 'success'
            });
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';
.fj-field-relation {
    margin-left: -$card-spacer-x;
    margin-right: -$card-spacer-x;

    .fj-index-table {
        background-color: transparent;
    }

    &-tags {
        border: $input-border-width solid $input-border-color;
        border-radius: $input-border-radius;
        padding: map-get($spacers, 3);
        padding-top: 0;
        width: 100%;
    }

    &-link {
        a {
            font-weight: 600;
        }
    }

    @media (max-width: map-get($grid-breakpoints, $nav-breakpoint-mobile)) {
        margin-left: 0;
        margin-right: 0;
        .fj-index-table {
            .card-body {
                padding: 0;
            }
        }
    }
}
</style>
