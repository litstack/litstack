<template>
    <div class="fj-block-header d-flex">
        <table class="form-control-expand">
            <b-tr>
                <b-td class="small" v-if="!field.readonly">
                    <div class="fj-draggable__dragbar" v-if="sortable">
                        <i class="fas fa-grip-horizontal text-muted"></i>
                    </div>
                </b-td>
                <fj-table-col
                    v-for="(col, i) in preview"
                    :key="i"
                    :item="block"
                    :col="col"
                    :cols="preview"
                />
                <b-td class="small text-secondary pl-4" v-if="!field.readonly">
                    <fa-icon
                        :icon="deleteIcon"
                        @click="$emit('deleteItem')"
                        class="fj-block-delete"
                    />
                </b-td>
                <b-td class="small pl-4" v-if="fields.length > 0">
                    <b-button
                        variant="outline-secondary"
                        size="sm"
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

                &:nth-child(2) {
                    transform: translateX(-16px);
                }
            }
        }
    }
    .fj-block-delete {
        cursor: pointer;
    }
    .fj-draggable__dragbar {
        float: left;
        transform: translateX(calc(-#{$card-spacer-x / 2} - 4px));
    }
}
</style>
