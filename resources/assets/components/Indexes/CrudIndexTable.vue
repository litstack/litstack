<template>
    <div class="fj-crud-index-table">

        <b-input-group class="mb-2">
            <b-input-group-prepend is-text>
                <fa-icon icon="search"/>
            </b-input-group-prepend>

            <b-form-input :placeholder="`Filter ${title}`" v-model="search"/>

        </b-input-group>

        <b-table-simple
            :aria-busy="isBusy"
            striped>

            <fj-colgroup
                :icons="[]"
                :cols="tableCols"/>

            <thead>
                <b-tr>
                    <b-th v-for="(col, key) in tableCols" :key="key">
                        {{ col.label }}
                    </b-th>
                </b-tr>
            </thead>

            <tbody>
                <tr role="row" class="b-table-busy-slot" v-if="isBusy">
                    <td :colspan="tableCols.length" role="cell" align="center">
                        <fj-spinner class="text-center"/>
                    </td>
                </tr>
                <template v-else>
                    <b-tr v-for="(item, key) in items" :key="key">
                        <b-td
                            v-for="(col, col_key) in tableCols"
                            :key="col_key"
                            @click="openItem(item, col.key != 'check')"
                            :class="col.key != 'check' ? 'pointer' : ''">
                            <div v-if="col.key == 'check'">

                            </div>
                            <div v-else>
                                <fj-table-col :item="item" :col="col"/>
                            </div>
                        </b-td>
                    </b-tr>
                </template>
            </tbody>
            <!--
            <b-button-group
                slot="[actions]"
                slot-scope="row"
                size="sm"
            >
                <b-button
                    variant="outline-primary"
                    :href="editLink(row.item.id)"
                    v-if="hasAction('edit')">
                    <fa-icon icon="edit" />
                </b-button>
                <b-button
                    variant="outline-danger"
                    @click="deleteItem(row.item)"
                    v-if="hasAction('delete')">
                    <fa-icon icon="trash" />
                </b-button>
            </b-button-group>

            <template slot="[]" slot-scope="row">
                {{ row.item[row.field.key] }}
            </template>
            -->
        </b-table-simple>
    </div>
</template>

<script>
import TableModel from './../../eloquent/table.model'

export default {
    name: 'CrudIndexTable',
    props: {
        title: {
            type: String,
            required: true
        },
        route: {
            type: String,
            required: true
        },
        cols: {
            required: true,
            type: Array
        },
        actions: {
            type: Array,
            default: []
        }
    },
    data() {
        return {
            isBusy: true,
            tableCols: {},
            items: [],
            search: ''
        };
    },
    watch: {
        search(val) {
            let self = this
            setTimeout(function() {
                if(self.search == val) {
                    self.loadItems()
                }
            }, 500)
        }
    },
    beforeMount() {
        this.tableCols = this.cols;

        /*
        if (this.actions.length != 0) {
            this.tableFields.push({
                key: 'actions',
                label: '',
                sortable: false
            });
        }
        */
       this.loadItems()
    },
    methods: {
        async loadItems() {
            this.isBusy = true

            let payload = {
                search: this.search
            }

            let response = await axios.post(`${this.route}/index`, payload)

            let items = []
            for(let i=0;i<response.data.length;i++) {
                items.push(new TableModel(response.data[i]))
            }
            this.items = items

            this.isBusy = false
        },

        hasAction(action) {
            return this.actions.includes(action);
        },
        deleteItem(item) {
            item.delete();
            this.$notify({
                group: 'general',
                type: 'success',
                title: `Deleted ${item.route}.`,
                text: '',
                duration: 1500
            });
        },
        openItem(item, open) {
            if(!open) {
                return
            }

            window.location.href = `${this.route}/${item.id}/edit`
        }
    }
};
</script>

<style lang="scss">
@import '../../sass/_variables';
.fj-crud-index-table{
    table.b-table{
        &[aria-busy='true']{
            opacity: 0.6;
        }

        thead th{
            border-top: none;
        }

        tbody{
            td{
                vertical-align: middle;

                &.pointer{
                    cursor: pointer;
                }
            }
        }

        .b-table-busy-slot{
            height: 300px;

            td{
                vertical-align: middle;
            }
        }
    }
}
</style>
