<template>
    <fj-base-container>
        <fj-base-header :title="config.names.plural">
            <div slot="actions-right">
                <component
                    v-for="(component, key) in headerComponents"
                    :key="key"
                    :is="component"
                    :config="config"
                />

                <b-button
                    size="sm"
                    variant="primary"
                    :href="`${baseURL}${config.route_prefix}/create`"
                >
                    <fa-icon icon="plus" />
                    {{ $t('fj.add_model', { model: config.names.singular }) }}
                </b-button>
            </div>
        </fj-base-header>

        <b-row>
            <b-col>
                <fj-index-table
                    ref="indexTable"
                    :cols="config.index"
                    :items="items"
                    :count="count"
                    :per-page="config.perPage"
                    :load-items="loadItems"
                    :name-singular="config.names.singular"
                    :name-plural="config.names.plural"
                    :sort-by="config.sortBy"
                    :sort-by-default="config.sortByDefault"
                    :filter="config.filter"
                    :global-actions="config.globalActions"
                    :record-actions="config.recordActions"
                />
            </b-col>
        </b-row>
    </fj-base-container>
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
        headerComponents: {
            required: true,
            type: Array
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
        reload() {
            this.$refs.indexTable.$emit('reload');
        },
        async loadItems(payload) {
            let response = await axios.post(
                `${this.config.route_prefix}/index`,
                payload
            );
            this.items = response.data.items;
            this.count = response.data.count;

            return response;
        }
    }
};
</script>

<style></style>
