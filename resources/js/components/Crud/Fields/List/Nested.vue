<template>
    <draggable
        class="fj-list"
        handle=".fj-list__dragbar"
        tag="ul"
        :list="children"
        :group="{ name: 'g1' }"
    >
        <li v-for="item in children" :key="item.id" class="">
            <div class="fj-list-entry fj-block mb-2">
                <table class="w-100">
                    <b-td class="col-sm" v-if="!field.readonly">
                        <i
                            class="fas fa-grip-horizontal text-secondary fj-list__dragbar"
                        ></i>
                    </b-td>
                    <b-td>
                        <div class="pl-2 d-inline-block">
                            {{ item.attributes.id }}
                        </div>
                    </b-td>
                    <b-td
                        class="col-sm text-secondary pl-2"
                        v-if="!field.readonly"
                    >
                        <b-button
                            variant="transparent"
                            v-b-tooltip
                            :title="$t('fj.delete_model', { model: 'Item' })"
                            size="sm"
                            class="btn-square fj-block-delete"
                            @click="$emit('deleteItem', item)"
                        >
                            <fa-icon icon="trash" />
                        </b-button>
                    </b-td>
                </table>
            </div>
            <nested-draggable
                :children="item.children"
                :field="field"
                :model="model"
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
            type: Object
        },

        /**
         * Model.
         */
        model: {
            required: true,
            type: Object
        },

        /**
         * Children.
         */
        children: {
            required: true,
            type: Array
        }
    },
    components: {
        draggable
    }
};
</script>

<style lang="scss" scoped>
ul.fj-list {
    width: 100%;
    padding: 0;
    list-style-type: none;
    ul.fj-list {
        padding-left: 2rem;
    }
}
.fj-list-entry {
    padding: 1rem;
}
.fj-list__dragbar {
    cursor: grab;
}
</style>
