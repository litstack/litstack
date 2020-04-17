<template>
    <b-table-simple
        outlined
        table-variant="light"
        :class="{ 'mb-0': readonly }"
    >
        <template v-for="(relations, model) in items">
            <b-tr v-for="(relation, key) in relations" :key="key">
                <fj-table-col
                    v-for="(col, i) in field.preview"
                    :key="i"
                    :item="relation"
                    :col="col"
                    :cols="field.preview"
                />
                <b-td class="reduce position-relative">
                    <div class="d-flex table-controls">
                        <b-button-group size="sm">
                            <b-button
                                :href="editUrl(relation, model)"
                                class="btn-transparent d-flex align-items-center"
                            >
                                <fa-icon icon="eye" />
                            </b-button>
                            <b-button
                                v-if="!readonly"
                                class="btn-transparent"
                                @click="
                                    field.confirm
                                        ? showModal(
                                              `modal-${routePrefix(model)}-${
                                                  relation.id
                                              }`
                                          )
                                        : removeRelation({
                                              id: relation.id,
                                              modelName: model
                                          })
                                "
                            >
                                <fa-icon icon="unlink" />
                            </b-button>
                            <fj-form-relation-confirm-delete
                                :relation="relation"
                                :model="model"
                                :routePrefix="routePrefix(model)"
                                @confirmed="removeRelation"
                            />
                        </b-button-group>
                    </div>
                </b-td>
            </b-tr>
        </template>
    </b-table-simple>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FormRelationIndex',
    props: {
        items: {
            required: true,
            type: Object
        },
        readonly: {
            required: true,
            type: Boolean
        },
        field: {
            required: true,
            type: Object
        },
        routePrefixes: {
            required: true,
            type: Object
        }
    },
    computed: {
        ...mapGetters(['baseURL'])
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
        removeRelation({ id, modelName }) {
            this.$emit('removeRelation', { id, modelName });
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
