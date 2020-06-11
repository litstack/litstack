<template>
    <fj-col :width="width">
        <fj-index-table
            ref="indexTable"
            v-bind="table"
            :items="items"
            :name-singular="config.names.singular"
            :name-plural="config.names.plural"
            :load-items="loadItems"
            @sorted="sorted"
        />
    </fj-col>
</template>

<script>
/*
:cols="cols"
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
*/
export default {
    name: 'CrudIndexTable',
    props: {
        table: {
            type: Object
        },
        config: {
            type: Object
        },
        width: {
            type: [Number],
            default() {
                return 12;
            }
        }
    },
    data() {
        return {
            items: [],
            count: 0
        };
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
