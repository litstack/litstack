<template>
    <transition name="slide">
        <div v-if="selectedItems.length > 0">
            <b-input-group size="sm" class="pt-1 pb-3">
                <b-input-group-prepend is-text>
                    <strong>
                        {{
                            trans_choice(
                                'crud.n_items_selected',
                                selectedItems.length
                            )
                        }}
                        {{
                            selectedItems.length == items.length
                                ? `(${__('base.all')})`
                                : ''
                        }}
                    </strong>
                </b-input-group-prepend>
                <template v-slot:append>
                    <b-dropdown
                        style="margin-left: 1px;"
                        size="sm"
                        :text="trans_choice('base.action', actions.length)"
                        class="btn-brl-none"
                        variant="outline-secondary"
                    >
                        <lit-base-component
                            v-for="(component, key) in actions"
                            :key="key"
                            :component="component"
                            :selectedItems="selectedItems"
                            :event-data="{ ids: _.map(selectedItems, 'id') }"
                            @eventHandled="reload"
                        />
                    </b-dropdown>
                </template>
            </b-input-group>
        </div>
    </transition>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'BaseIndexTableSelectedItemsActions',
    props: {
        items: {
            required: true,
            type: [Object, Array],
        },
        selectedItems: {
            type: Array,
            required: true,
        },
        actions: {
            required: true,
            type: Array,
        },
    },
    methods: {
        reload() {
            this.$emit('reload');
        },
    },
};
</script>

<style lang="css" scoped>
.slide-enter-active {
    -moz-transition-duration: 0.3s;
    -webkit-transition-duration: 0.3s;
    -o-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -moz-transition-timing-function: ease-in;
    -webkit-transition-timing-function: ease-in;
    -o-transition-timing-function: ease-in;
    transition-timing-function: ease-in;
}

.slide-leave-active {
    -moz-transition-duration: 0.3s;
    -webkit-transition-duration: 0.3s;
    -o-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -moz-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
    -webkit-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
    -o-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
    transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
}

.slide-enter-to,
.slide-leave {
    max-height: 100px;
    overflow: hidden;
}

.slide-enter,
.slide-leave-to {
    overflow: hidden;
    max-height: 0;
}
</style>
