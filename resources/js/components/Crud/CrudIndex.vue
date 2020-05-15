<template>
    <fj-container :fluid="config.expand ? 'fluid' : 'lg'">
        <fj-navigation :controls="slots.navControls">
            <b-button
                size="md"
                variant="primary"
                slot="right"
                :href="`${baseURL}${config.route_prefix}/create`"
            >
                <fa-icon icon="plus" />
                {{ $t('fj.add_model', { model: config.names.singular }) }}
            </b-button>
        </fj-navigation>
        <fj-header :title="config.names.plural">
            <div slot="actions" class="d-flex align-items-center">
                <fj-slot
                    v-for="(component, key) in slots.headerControls"
                    :key="key"
                    v-bind="component"
                    :config="config"
                />
            </div>
        </fj-header>

        <b-row>
            <b-col>
                <fj-index-table
                    ref="indexTable"
                    :cols="config.index"
                    :items="items"
                    :count="count"
                    :sortable="config.sortable"
                    :order-column="config.orderColumn"
                    :per-page="config.perPage"
                    :load-items="loadItems"
                    :name-singular="config.names.singular"
                    :name-plural="config.names.plural"
                    :sort-by="config.sortBy"
                    :sort-by-default="config.sortByDefault"
                    :filter="config.filter"
                    :controls="slots.indexControls"
                    @sorted="sorted"
                />
            </b-col>
        </b-row>
    </fj-container>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudIndex',
    props: {
        config: {
            required: true,
            type: Object
        },
        slots: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            items: [],
            count: 0
        };
    },
    beforeMount() {
        this.$store.commit('SET_CONFIG', this.config);
    },
    computed: {
        ...mapGetters(['baseURL'])
    },
    methods: {
        async sorted({ sortedItems, ids }) {
            this.items = sortedItems;
            try {
                let response = axios.post(`${this.config.route_prefix}/order`, {
                    ids: ids
                });
            } catch (e) {
                console.log(e);
                return;
            }

            this.$bvToast.toast(this.__('fj.order_changed'), {
                variant: 'success'
            });
        },
        reload() {
            this.$refs.indexTable.$emit('reload');
        },
        async loadItems(payload) {
            let response = await axios.post(
                `${this.config.route_prefix}/index`,
                payload
            );
            this.items = this.crud(response.data.items);
            this.count = response.data.count;

            return response;
        }
    }
};
</script>
