<template>
    <lit-col :width="width">
        <lit-index-table
            ref="table"
            v-bind="table"
            :items="items"
            :name-singular="table.singularName"
            :name-plural="table.pluralName"
            :load-items="loadItems"
            @sorted="sorted"
        />
    </lit-col>
</template>

<script>
export default {
    name: 'PageTable',
    props: {
        table: {
            type: Object,
            required: true,
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
        async loadItems(payload) {
            let response = await this.sendLoadItems(payload);

            if (!response) {
                return;
            }

            this.items = this.crud(response.data.items);
            this.count = response.data.count;

            return response;
        },
        async sendLoadItems(payload) {
            try {
                return await axios.post(
                    `${this.table.route_prefix}/index`,
                    payload
                );
            } catch (e) {
                console.log(e);
            }
        },
        async sorted({ sortedItems, ids }) {
            let response = await this.sendSorted(ids);

            if (!response) {
                return;
            }

            this.items = sortedItems;

            this.$bvToast.toast(this.__('base.messages.order_changed'), {
                variant: 'success',
            });
        },
        async sendSorted(ids) {
            try {
                return await axios.post(`${this.table.route_prefix}/order`, {
                    ids: ids,
                });
            } catch (e) {
                console.log(e);
            }
        },
        reload() {
            this.$refs.table.$emit('reload');
        },
    },
};
</script>
