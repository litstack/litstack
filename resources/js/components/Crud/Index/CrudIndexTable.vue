<template>
    <lit-col :width="width">
        <lit-index-table
            ref="indexTable"
            v-bind="table"
            :items="items"
            :name-singular="config.names.singular"
            :name-plural="config.names.plural"
            :load-items="loadItems"
            @sorted="sorted"
        />
    </lit-col>
</template>

<script>
export default {
    name: 'CrudIndexTable',
    props: {
        table: {
            type: Object,
        },
        config: {
            type: Object,
        },
        width: {
            type: [Number],
            default() {
                return 12;
            },
        },
    },
    data() {
        return {
            items: [],
            count: 0,
        };
    },
    methods: {
        async sorted({ sortedItems, ids }) {
            this.items = sortedItems;
            try {
                let response = axios.post(`${this.config.route_prefix}/order`, {
                    ids: ids,
                });
            } catch (e) {
                console.log(e);
                return;
            }

            this.$bvToast.toast(this.__('base.messages.order_changed'), {
                variant: 'success',
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
        },
    },
};
</script>
