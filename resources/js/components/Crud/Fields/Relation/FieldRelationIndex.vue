<template>
    <div>
        <draggable
            v-model="sortableRelations"
            @end="newOrder(relations)"
            tag="tbody"
            handle=".lit-draggable__dragbar"
        >
            <lit-field-block
                v-for="(relation, key) in relations"
                :key="key"
                :block="relation"
                :field="field"
                :model="model"
                :set-route-prefix="setFieldsRoutePrefixBlockId"
                @deleteItem="deleteBlock"
            />
        </draggable>
    </div>
    <!--
    <b-table-simple outlined hover :class="{ 'mb-0': field.readonly }">
        <template v-for="(relations, model) in selectedItems">
            <draggable
                v-model="sortableRelations"
                @end="newOrder(relations)"
                tag="tbody"
                handle=".lit-draggable__dragbar"
            >
                <b-tr v-for="(relation, key) in relations" :key="key">
                    <b-td
                        v-if="field.sortable && !field.readonly"
                        class="lit-draggable__dragbar position-relative"
                        style="vertical-align:middle;"
                    >
                        <div class="text-center text-muted">
                            <lit-fa-icon icon="grip-vertical" />
                        </div>
                    </b-td>

                    <lit-table-col
                        v-for="(col, i) in field.preview"
                        :key="i"
                        :item="relation"
                        :col="col"
                        :cols="field.preview"
                    />
                    <b-td class="col-sm position-relative">
                        <div class="d-flex table-controls">
                            <b-button-group size="sm">
                                <b-button
                                    :href="editUrl(relation, model)"
                                    class="btn-transparent d-flex align-items-center"
                                >
                                    <lit-fa-icon icon="eye" />
                                </b-button>
                                <b-button
                                    v-if="!field.readonly"
                                    class="btn-transparent"
                                    @click="
                                        field.confirm
                                            ? showModal(
                                                  `modal-${routePrefix(
                                                      model
                                                  )}-${relation.id}`
                                              )
                                            : removeRelation({
                                                  id: relation.id,
                                                  modelName: model
                                              })
                                    "
                                >
                                    <lit-fa-icon icon="unlink" />
                                </b-button>
                                <lit-field-relation-confirm-delete
                                    :relation="relation"
                                    :model="model"
                                    :routePrefix="routePrefix(model)"
                                    @confirmed="removeRelation"
                                />
                            </b-button-group>
                        </div>
                    </b-td>
                </b-tr>
            </draggable>
        </template>
    </b-table-simple>
    -->
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FieldRelationIndex',
    props: {
        model: {
            type: Object,
        },
        modelId: {
            required: true,
        },
        items: {
            required: true,
            type: Object,
        },
        field: {
            required: true,
            type: Object,
        },
        routePrefixes: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            selectedItems: {},
            sortableRelations: [],
        };
    },
    watch: {
        items() {
            this.selectedItems = this.items;
            if (this.field.model) {
                this.sortableRelations = this.items[this.field.model];
            }
        },
    },
    beforeMount() {
        this.selectedItems = this.items;
        if (this.field.model) {
            this.sortableRelations = this.items[this.field.model];
        }
    },
    computed: {
        ...mapGetters(['baseURL', 'config']),
    },
    methods: {
        showModal(id) {
            this.$bvModal.show(id);
        },
        editUrl(relation, model) {
            return `${this.baseURL}${this.routePrefix(model)}/${
                relation.id
            }/edit`;
        },
        routePrefix(model) {
            return this.routePrefixes[model];
        },
        removeRelation(payload) {
            this.$emit('removeRelation', payload);
        },
        async newOrder(relations) {
            let ids = [];

            this.selectedItems[this.field.model] = this.sortableRelations;

            for (let i = 0; i < this.sortableRelations.length; i++) {
                let relation = this.sortableRelations[i];
                ids.push(relation.id);
            }

            let payload = { ids };

            let response = await axios.put(
                `${this.field.route_prefix}/${this.field.id}/order`,
                payload
            );

            this.$bvToast.toast(this.__('base.messages.order_changed'), {
                variant: 'success',
            });
        },
    },
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
