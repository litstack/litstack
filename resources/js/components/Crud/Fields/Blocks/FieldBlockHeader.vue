<template>
    <div class="fj-block-header d-flex">
        <table class="form-control-expand">
            <b-tr>
                <b-td class="col-sm" v-if="!field.readonly">
                    <div
                        class="fj-draggable__dragbar fj-block__dragbar"
                        v-b-tooltip
                        :title="$t('fj.change_order')"
                        v-if="sortable"
                    >
                        <i class="fas fa-grip-horizontal text-secondary"></i>
                    </div>
                </b-td>
                <fj-table-col
                    ref="cols"
                    v-for="(col, i) in preview"
                    :key="i"
                    :item="block"
                    :col="col"
                    :cols="preview"
                />
                <b-td class="col-sm text-secondary pl-2" v-if="!field.readonly">
                    <b-button
                        variant="transparent"
                        v-b-tooltip
                        :title="$t('fj.delete_model', { model: 'Block' })"
                        size="sm"
                        class="btn-square fj-block-delete"
                        @click="$emit('deleteItem')"
                    >
                        <fa-icon :icon="deleteIcon" />
                    </b-button>
                </b-td>
                <b-td class="col-sm pl-2 pr-0" v-if="fields.length > 0">
                    <b-button
                        variant="outline-secondary"
                        :title="$t('crud.fields.blocks.expand')"
                        size="sm"
                        class="btn-square"
                        @click="$emit('toggleExpand')"
                    >
                        <fa-icon :icon="expand ? 'angle-up' : 'angle-down'" />
                    </b-button>
                </b-td>
            </b-tr>
        </table>
    </div>
</template>

<script>
export default {
    name: 'FieldBlockHeader',
    props: {
        deleteIcon: {
            type: String,
            required: true
        },
        sortable: {
            type: Boolean,
            required: true
        },
        expand: {
            required: true,
            type: Boolean
        },
        block: {
            required: true,
            type: Object
        },
        field: {
            required: true,
            type: Object
        },
        fields: {
            required: true,
            type: Array
        },
        model: {
            required: true,
            type: Object
        },
        preview: {
            type: Array,
            required: true
        }
    },
    beforeMount() {
        this.$on('refresh', this.refresh);
    },
    methods: {
        /**
         * Pass refresh event to fj-table-col components.
         */
        refresh() {
            for (let i in this.$refs.cols) {
                this.$refs.cols[i].$emit('refresh');
            }
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';

.fj-block {
    .fj-block-header {
        table {
            height: 100%;
            td {
                font-weight: 500;
                &:first-child {
                    width: 0;
                    padding: 0px;
                    position: relative;
                    div {
                        position: absolute;
                        top: 0;
                        height: 100%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        width: 2.5rem;
                        left: -2.5rem;
                    }
                }
            }
        }
    }
    .fj-block-delete {
        cursor: pointer;
        color: $secondary;
    }
}
</style>
