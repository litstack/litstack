<template>
    <transition name="slide">
        <b-input-group v-if="selectedItems.length > 0" :size="'sm'">
            <b-input-group-prepend is-text>
                <strong>
                    {{ $tc('fj.n_items_selected', selectedItems.length) }}
                    {{
                        selectedItems.length == items.length
                            ? `(${$t('fj.all')})`
                            : ''
                    }}
                </strong>
            </b-input-group-prepend>

            <template v-slot:append>
                <b-dropdown
                    text="Actions"
                    class="btn-brl-none"
                    variant="outline-secondary"
                >
                    <component
                        v-for="(component, key) in actions"
                        :key="key"
                        :is="component"
                        :selectedItems="selectedItems"
                        :sendAction="sendAction"
                        @reload="reload"
                    />
                </b-dropdown>
            </template>
        </b-input-group>
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
            required: true
        },
        actions: {
            required: true,
            type: Array
        },
    },
    methods: {
        async sendAction(route, ids) {
            let response = null;
            let message = '';
            let type = 'success';
            try {
                response = await _axios({
                    method: 'post',
                    url: route,
                    data: { ids }
                });

                message = response.data.message;
            } catch (e) {
                response = e.response;
                message = response.data.message;
                type = 'danger';
            }

            this.$bvToast.toast(message, {
                variant: 'info'
            });
        },
        reload() {
            this.$emit('reload')
        }
    }
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
