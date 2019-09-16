<template>
    <div class="fj-crud-index-table">

        <div class="fj-crud-index-table__form mb-2">
            <b-input-group>

                <b-input-group-prepend is-text>
                    <fa-icon icon="search"/>
                </b-input-group-prepend>

                <b-form-input :placeholder="`Filter ${names.title.plural}`" v-model="search"/>

            </b-input-group>

            <b-dropdown
                no-caret
                variant="light"
                size="sm"
                class="ml-3"
                v-if="config.sort_by">

                <template slot="button-content">
                    <fa-icon icon="sort"/> Sort
                </template>

                    <b-dropdown-item v-for="(text, key) in config.sort_by" :key="key" @click="sortBy(key)">
                        <b-form-radio :checked="sort_by_key" :value="key">{{ text }}</b-form-radio>
                    </b-dropdown-item>

            </b-dropdown>
        </div>


        <b-table-simple
            :aria-busy="isBusy">

            <fj-colgroup
                :icons="['check']"
                :cols="tableCols"/>

            <thead>
                <b-tr>
                    <b-th
                        v-if="col.key == 'check' || selectedItems.length == 0"
                        v-for="(col, key) in tableCols"
                        :colspan="(col.key == 'check' && selectedItems.length > 0) ? tableCols.length : 1"
                        :key="key">
                        <template v-if="col.key == 'check'">

                            <b-checkbox
                                class="float-left"
                                v-model="selectedAll"
                                :indeterminate.sync="indeterminateSelectedItems"
                                @change="changeSelectedItems"
                                v-if="col.key == 'check' && selectedItems.length == 0"/>

                            <b-input-group
                                v-if="col.key == 'check' && selectedItems.length > 0"
                                class="fj-index-checkbox__group"
                                size="sm">
                                <b-input-group-prepend is-text>
                                    <b-checkbox
                                        class="float-left"
                                        v-model="selectedAll"
                                        :indeterminate.sync="indeterminateSelectedItems"
                                        @change="changeSelectedItems"/>
                                </b-input-group-prepend>
                                <b-input-group-prepend is-text>
                                    <strong>
                                        {{ selectedItems.length }} {{ selectedItems.length == 1 ? ' Item' : ' Items' }} selected
                                    </strong>
                                </b-input-group-prepend>

                                <b-dropdown
                                    no-caret
                                    variant="primary">

                                    <template slot="button-content">
                                        Actions
                                    </template>

                                    <slot name="actions"/>

                                    <!--
                                    <b-dropdown-item
                                        v-for="(route, title) in actions"
                                        :key="title"
                                        @click="action(selectedItems, {route, title})">
                                        {{ title }}
                                    </b-dropdown-item>
                                    -->

                                </b-dropdown>

                            </b-input-group>

                        </template>
                        <template v-else>

                            {{ col.label }}

                        </template>
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
                    <b-tr
                        v-for="(item, key) in items"
                        :key="key"
                        :class="selectedItems.includes(item.id) ? 'table-primary' : ''">
                        <template
                            v-for="(col, col_key) in tableCols">
                            <b-td
                                v-if="col.key == 'check'">
                                <b-checkbox v-model="selectedItems" :value="item.id"/>
                            </b-td>
                            <b-td
                                v-else
                                @click="openItem(item)"
                                class="pointer">

                                <fj-table-col :item="item" :col="col"/>

                            </b-td>
                        </template>

                    </b-tr>
                </template>
            </tbody>
        </b-table-simple>
    </div>
</template>

<script>
import TableModel from './../../eloquent/table.model'

export default {
    name: 'CrudIndexTable',
    props: {
        names: {
            type: Object,
            required: true
        },
        config: {
            type: Object,
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
            type: Object,
            default: () => {
                return {}
            }
        }
    },
    data() {
        return {
            typingDelay: 500,
            isBusy: true,
            tableCols: {},
            items: [],
            search: '',
            sort_by_key: '',
            selectedItems: [],
            indeterminateSelectedItems: false,
            selectedAll: false,
        };
    },
    watch: {
        search(val) {
            let self = this
            setTimeout(function() {
                if(self.search == val) {
                    self.loadItems()
                }
            }, this.typingDelay)
        },
        selectedItems(val) {
            this.$emit('selectedItemsChanged', val)

            if(val.length == this.items.length) {
                this.selectedAll = true
                this.indeterminateSelectedItems = false
                return
            }

            this.selectedAll = false
            this.indeterminateSelectedItems = val.length > 0
                ? true
                : false;
        },
    },
    beforeMount() {
        this.setTableCols();

        this.sort_by_key = this.config.sort_by_default || null

        this.loadItems()

        this.$bus.$on('reloadCrudIndex', this.loadItems)
        this.$bus.$on('unselectCrudIndex', () => {
            this.selectedItems = []
        })
    },
    methods: {
        changeSelectedItems(val) {
            if(val) {
                this.selectedItems = this.items.map((item) => {return item.id})
            } else {
                this.selectedItems = []
            }
        },
        setTableCols() {
            this.tableCols = []

            this.tableCols.push({
                key: 'check',
                label: 'Check',
            })

            for(let i=0;i<this.cols.length;i++) {
                let col = this.cols[i]

                if(typeof col == typeof '') {
                    col = {key: col, title: col.capitalize()}
                }

                this.tableCols.push(col)
            }
        },
        async loadItems() {
            this.isBusy = true

            let payload = {
                search: this.search,
                sort_by: this.sort_by_key
            }

            let response = await axios.post(`${this.route}/index`, payload)

            let items = []
            for(let i=0;i<response.data.length;i++) {
                items.push(new TableModel(response.data[i]))
            }
            this.items = items

            this.isBusy = false
        },
        sortBy(key) {
            this.sort_by_key = key
            this.loadItems()
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
        openItem(item) {
            window.location.href = `${this.route}/${item.id}` + ('route' in this.config ? this.config.route : '/edit')
        }
    }
};
</script>

<style lang="scss">
@import '../../sass/_variables';
.fj-crud-index-table{
    table.b-table{
        width: auto;
        margin-left: -1.25rem;
        margin-right: -1.25rem;

        &[aria-busy='true']{
            opacity: 0.6;
        }

        thead th{
            border-top: none;

            &:first-child{
                padding-left: 0.75rem + 1.25rem;
            }

            &:last-child{
                padding-right: 0.75rem + 1.25rem;
            }
        }

        tbody{
            td{
                vertical-align: middle;

                &.pointer{
                    cursor: pointer;
                }

                &:first-child{
                    padding-left: 0.75rem + 1.25rem;
                }

                &:last-child{
                    padding-right: 0.75rem + 1.25rem;
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

    &__form{
        display:flex;
        flex-direction: row;
        align-items: stretch;

        .input-group{
            flex: 1;
        }
    }
}

.fj-index-checkbox__group{
    width: auto;
    margin-top: -7px;
    transform: translateX(-0.75rem);

    .input-group-prepend{
        &:first-child {

            .input-group-text{
                padding-left: calc(0.75rem - 1px);
                //width: 37px;
                background: transparent;
            }
        }
    }
}
</style>
