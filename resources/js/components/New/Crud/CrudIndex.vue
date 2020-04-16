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
                    :searchKeys="config.search"
                    :loadItems="loadItems"
                    :nameSingular="config.names.singular"
                    :namePlural="config.names.plural"
                    :sortBy="config.sortBy"
                    :sortByDefault="config.sortByDefault"
                    :filter="config.filter"
                    :globalActions="config.globalActions"
                    :recordActions="config.recordActions"
                />
            </b-col>
        </b-row>
    </fj-base-container>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'NewCrudIndex',
    props: {
        config: {
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
        }
    }
};
</script>

<style></style>
