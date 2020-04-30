<template>
    <div class="fj-block-header d-flex">
        <table class="form-control-expand">
            <b-tr>
                <b-td class="reduce" v-if="!field.readonly">
                    <div class="fj-draggable__dragbar">
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
                <b-td class="reduce text-secondary px-4" v-if="!field.readonly">
                    <fa-icon
                        icon="trash"
                        @click="$emit('deleteBlock')"
                        class="fj-block-delete"
                    />
                </b-td>
                <b-td class="reduce">
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
        model: {
            required: true,
            type: Object
        }
    },
    computed: {
        preview() {
            return this.field.repeatables[this.block.type].preview;
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
