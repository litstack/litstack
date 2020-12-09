<template>
    <draggable
        class="lit-list"
        handle=".lit-list__dragbar"
        tag="ul"
        :list="children"
        :group="{ name: 'g1' }"
        v-on="$listeners"
    >
        <li v-for="item in children" :key="item.id" class="">
            <div class="lit-list-entry lit-block mb-2">
                <table class="w-100">
                    <b-td class="col-sm" v-if="!field.readonly">
                        <i
                            class="fas fa-grip-horizontal text-secondary lit-list__dragbar"
                        ></i>
                    </b-td>
                    <b-td>
                        <div class="pl-2 d-inline-block">
                            {{ _format(field.previewTitle, item) }}
                        </div>
                    </b-td>
                    <b-td
                        class="col-sm text-secondary pl-2"
                        v-if="!field.readonly"
                    >
                        <b-button
                            variant="transparent"
                            v-b-tooltip
                            :title="__('base.item_edit', { item: 'Item' })"
                            size="sm"
                            class="btn-square lit-block-delete"
                            v-b-modal="modalId(item)"
                        >
                            <lit-fa-icon icon="edit" />
                        </b-button>
                        <b-button
                            variant="transparent"
                            v-b-tooltip
                            :title="__('base.item_add', { item: 'Item' })"
                            size="sm"
                            class="btn-square lit-block-delete"
                            @click="$emit('addItem', item)"
                        >
                            <lit-fa-icon icon="plus" />
                        </b-button>
                        <b-button
                            variant="transparent"
                            v-b-tooltip
                            :title="__('base.item_delete', { item: 'Item' })"
                            size="sm"
                            class="btn-square lit-block-delete"
                            v-b-modal="`${modalId(item)}-delete`"
                        >
                            <lit-fa-icon icon="trash" />
                        </b-button>
                    </b-td>
                </table>
                <lit-field-list-modal
                    :item="item"
                    :model="model"
                    :modal-id="modalId(item)"
                    :field="field"
                />
                <lit-field-list-modal-confirm-delete
                    :item="item"
                    :model="model"
                    :modal-id="`${modalId(item)}-delete`"
                    :field="field"
                    v-on="$listeners"
                />
            </div>
            <nested-draggable
                :children="item.children"
                :field="field"
                :model="model"
                v-on="$listeners"
            />
        </li>
    </draggable>
</template>
<script>
import draggable from 'vuedraggable';

export default {
    name: 'nested-draggable',
    props: {
        /**
         * Field attributes.
         */
        field: {
            required: true,
            type: Object,
        },

        /**
         * Model.
         */
        model: {
            required: true,
            type: Object,
        },

        /**
         * Children.
         */
        children: {
            required: true,
            type: Array,
        },
    },
    components: {
        draggable,
    },

    methods: {
        modalId(item) {
            return `list-modal-${this.field.route_prefix}-${this.field.local_key}-${item.id}`;
        },
    },
};
</script>

<style lang="scss" scoped>
ul.lit-list {
    width: 100%;
    padding: 0;
    list-style-type: none;
    ul.lit-list {
        padding-left: 2rem;
    }
}
.lit-list-entry {
    padding: 1rem;
}
.lit-list__dragbar {
    cursor: grab;
}
</style>
