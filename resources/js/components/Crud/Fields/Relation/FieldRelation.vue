<template>
    <fj-form-item :field="field" :model="model" class="">
        <template slot="title-right">
            <b-button
                variant="secondary"
                size="sm"
                v-b-modal="modalId"
                v-if="!field.readonly"
            >
                <fa-icon icon="plus" />
                {{
                    field.many
                        ? __('fj.add_model', { model: field.title })
                        : __('fj.select_item', { item: field.title })
                }}
            </b-button>
        </template>

        <div class="form-control-expand fj-field-relation" v-if="model.id">
            <!--
            <div v-if="busy" class="d-flex justify-content-around">
                <fj-spinner />
            </div>
            -->

            <template>
                <fj-index-table
                    ref="table"
                    :cols="field.preview"
                    :items="selectedRelations"
                    :load-items="loadRelations"
                    no-card
                    no-select
                    :sort-by-default="
                        field.sortable ? field.order_column + '.asc' : null
                    "
                    :name-singular="field.config.names.singular"
                    :name-plural="field.config.names.plural"
                    :searchKeys="field.searchable ? field.config.search : []"
                    :per-page="field.perPage"
                    v-bind:small="field.small"
                    :sortable="field.sortable ? 'force' : false"
                    @sorted="newOrder"
                    @unlink="removeRelation"
                />
                <!--
                <draggable
                    v-model="selectedRelations"
                    @end="newOrder"
                    handle=".fj-draggable__dragbar"
                    tag="b-row"
                    :class="{ 'mb-0': field.readonly }"
                >
                    <fj-field-block
                        ref="block"
                        v-for="(relation, index) in selectedRelations"
                        :key="index"
                        :block="relation"
                        :field="field"
                        :fields="field.form ? field.form.fields : []"
                        :model="model"
                        :cols="field.relatedCols"
                        :sortable="field.many ? field.sortable : false"
                        :preview="field.preview"
                        :set-route-prefix="setFieldsRoutePrefixId"
                        delete-icon="unlink"
                        @deleteItem="removeRelation(relation)"
                        @changed="$emit('changed')"
                    />
                </draggable>
                -->

                <fj-field-relation-confirm-delete
                    v-for="(relation, index) in selectedRelations"
                    :key="index"
                    :field="field"
                    :relation="relation"
                    :route-prefix="field.route_prefix"
                    @confirmed="_removeRelation"
                    @canceled="$refs.modal.$emit('refresh')"
                />
            </template>
            <!--
            <div v-if>
                <fj-field-alert-empty
                    :field="field"
                    :class="{ 'mb-0': field.readonly }"
                />
            </div>
            -->

            <fj-field-relation-modal
                ref="modal"
                :field="field"
                :model="model"
                :modal-id="modalId"
                :selectedRelations="allSelectedRelations"
                @select="selectRelation"
                @remove="removeRelation"
            />
        </div>
        <template v-else>
            <fj-field-alert-not-created :field="field" class="mb-0" />
        </template>
    </fj-form-item>
</template>

<script>
import methods from '../methods';

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
            allSelectedRelations: [],
            selectedRelations: [],
            busy: true,
            cols: []
        };
    },
    beforeMount() {
        this.cols = this.field.preview;
        this.cols.push({
            label: '',
            link: `${this.field.config.route_prefix}/{id}/edit`,
            value: '<i class="ml-4 fas fa-eye text-secondary"></i>',
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
        ...methods,
        toggleExpand() {
            for (let i in this.$refs.block) {
                let block = this.$refs.block[i];
                block.$emit('expand', !this.expandedAll);
            }

            this.expandedAll = !this.expandedAll;
        },
        async loadAllRelations() {
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
        },
        async loadRelations(payload) {
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
        newRelation(relation) {
            this.selectedRelations.push(this.crud(relation));
        },
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
                            relation: this.field.config.names.singular
                        }),
                        {
                            variant: 'success'
                        }
                    );
                    break;
                case 'hasOne':
                case 'morphOne':
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
                /*
                case 'morphTo':
                    this.model.attributes[this.field.morph_type] = modelName;
                    this.model.attributes[this.field.foreign_key] = item.id;
                    this.model[`${this.field.id}Model`] = item.attributes;
                    this.setCols();
                    this.$emit('changed', this.field, this.model);
                    break;
                */
                case 'belongsTo':
                    this.setValue(relation.id);
                    break;
            }

            if (!this.field.many) {
                this.allSelectedRelations = [];
                this.allSelectedRelations.push(relation);

                this.$bvModal.hide(this.modalId);
            } else {
                this.allSelectedRelations.push(relation);
            }
            this.$nextTick(() => {
                if (!this.field.form) {
                    return;
                }
                this.$refs.block[this.$refs.block.length - 1].$emit(
                    'expand',
                    true
                );
            });
            this.$refs.table.$emit('reload');
            this.$emit('reload', relation);
            Fjord.bus.$emit('field:updated', 'relation:selected');
        },
        removeRelation(relation) {
            if (!this.field.confirm) {
                return this._removeRelation(relation);
            }

            this.$bvModal.show(`modal-${this.field.id}-${relation.id}`);
        },
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
                    this.setValue(null);
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
            this.$refs.table.$emit('reload');
            this.$emit('reload');
            Fjord.bus.$emit('field:updated', 'relation:removed');
        },
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
    },
    computed: {
        modalId() {
            return `form-relation-table-${this.field.route_prefix.replace(
                /\//g,
                '-'
            )}`;
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
}
</style>
