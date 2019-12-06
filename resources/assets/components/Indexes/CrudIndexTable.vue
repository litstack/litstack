<template>
    <div class="fj-crud-index-table">
        <div class="fj-crud-index-table__form mb-2">
            <b-input-group>
                <b-input-group-prepend is-text>
                    <fa-icon icon="search" />
                </b-input-group-prepend>

                <b-form-input
                    :placeholder="`Filter ${names.title.plural}`"
                    v-model="search"
                />

                <template v-slot:append>
                    <b-dropdown
                        right
                        text="Filter"
                        class="btn-br-none"
                        variant="outline-secondary"
                    >
                        <b-dropdown-item>
                            Coming soon…
                        </b-dropdown-item>
                    </b-dropdown>
                    <b-dropdown
                        right
                        text="Sort"
                        class="btn-brl-none"
                        variant="outline-secondary"
                        v-if="config.sort_by"
                    >
                        <b-dropdown-item
                            v-for="(text, key) in config.sort_by"
                            :key="key"
                            @click="sortBy(key)"
                        >
                            <b-form-radio :checked="sort_by_key" :value="key">{{
                                text
                            }}</b-form-radio>
                        </b-dropdown-item>
                    </b-dropdown>
                </template>
            </b-input-group>
        </div>

        <fj-selected-items-actions
            :items="items"
            :selectedItems="selectedItems"
        >
            <slot name="actions" slot="actions" />
        </fj-selected-items-actions>

        <b-table-simple :aria-busy="isBusy">
            <fj-colgroup :icons="['check']" :cols="tableCols" />

            <fj-crud-index-table-head
                :tableCols="tableCols"
                :hasRecordActions="hasRecordActions"
                :selectedItems="selectedItems"
                @sort="sortCol"
            >
                <b-checkbox
                    slot="checkbox"
                    class="float-left"
                    v-model="selectedAll"
                    :indeterminate.sync="indeterminateSelectedItems"
                    @change="changeSelectedItems"
                />
            </fj-crud-index-table-head>

            <tbody>
                <tr role="row" class="b-table-busy-slot" v-if="isBusy">
                    <td :colspan="tableCols.length" role="cell" align="center">
                        <fj-spinner class="text-center" />
                    </td>
                </tr>
                <template v-else>
                    <tr
                        v-for="(item, key) in items"
                        :key="key"
                        :class="
                            selectedItems.includes(item.id)
                                ? 'table-primary'
                                : ''
                        "
                    >
                        <template v-for="(col, col_key) in tableCols">
                            <td v-if="col.key == 'check'">
                                <b-checkbox
                                    v-model="selectedItems"
                                    :value="item.id"
                                />
                            </td>
                            <td v-else @click="openItem(item)" class="pointer">
                                <fj-table-col :item="item" :col="col" />
                            </td>
                        </template>
                        <td v-if="hasRecordActions">
                            <component
                                v-for="(component, key) in recordActions"
                                :key="key"
                                :is="component"
                                :item="item"
                                @reload="loadItems"
                            />
                        </td>
                    </tr>
                </template>
            </tbody>
        </b-table-simple>
    </div>
</template>

<script>
import TableModel from './../../eloquent/table.model';

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
                return {};
            }
        },
        recordActions: {
            type: Array,
            default: () => {
                return [];
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
            selectedAll: false
        };
    },
    watch: {
        search(val) {
            let self = this;
            setTimeout(function() {
                if (self.search == val) {
                    self.loadItems();
                }
            }, this.typingDelay);
        },
        selectedItems(val) {
            this.$emit('selectedItemsChanged', val);

            if (val.length == this.items.length) {
                this.selectedAll = true;
                this.indeterminateSelectedItems = false;
                return;
            }

            this.selectedAll = false;
            this.indeterminateSelectedItems = val.length > 0 ? true : false;
        }
    },
    beforeMount() {
        this.setTableCols();

        this.sort_by_key = this.config.sort_by_default || null;

        this.loadItems();

        this.$bus.$on('reloadCrudIndex', this.loadItems);
        this.$bus.$on('unselectCrudIndex', () => {
            this.selectedItems = [];
        });
    },
    computed: {
        hasRecordActions() {
            return this.recordActions.length > 0;
        }
    },
    methods: {
        changeSelectedItems(val) {
            if (val) {
                this.selectedItems = this.items.map(item => {
                    return item.id;
                });
            } else {
                this.selectedItems = [];
            }
        },
        setTableCols() {
            this.tableCols = [];

            this.tableCols.push({
                key: 'check',
                label: 'Check'
            });

            for (let i = 0; i < this.cols.length; i++) {
                let col = this.cols[i];

                if (typeof col == typeof '') {
                    col = { key: col, title: col.capitalize() };
                }

                this.tableCols.push(col);
            }
        },
        async loadItems() {
            this.isBusy = true;

            let payload = {
                search: this.search,
                sort_by: this.sort_by_key,
                eagerLoad: this.config.load || []
            };

            let response = await axios.post(`${this.route}/index`, payload);

            let items = [];
            for (let i = 0; i < response.data.length; i++) {
                items.push(new TableModel(response.data[i]));
            }
            this.items = items;

            this.isBusy = false;
        },
        sortBy(key) {
            this.sort_by_key = key;
            this.loadItems();
        },
        sortCol(value) {
            this.sortBy(value);
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
            window.location.href =
                `${this.route}/${item.id}` +
                ('route' in this.config ? this.config.route : '/edit');
        }
    }
};
</script>

<style lang="scss">
@import '../../sass/_variables';
.fj-crud-index-table {
    table.b-table {
        width: auto;
        margin-left: -1.25rem;
        margin-right: -1.25rem;

        &[aria-busy='true'] {
            opacity: 0.6;
        }

        thead th {
            border-top: none;

            &:first-child {
                padding-left: 0.75rem + 1.25rem;
            }

            &:last-child {
                padding-right: 0.75rem + 1.25rem;
            }
        }

        tbody {
            td {
                vertical-align: middle;

                &.pointer {
                    cursor: pointer;
                }

                &:first-child {
                    padding-left: 0.75rem + 1.25rem;
                }

                &:last-child {
                    padding-right: 0.75rem + 1.25rem;
                }
            }
        }

        .b-table-busy-slot {
            height: 300px;

            td {
                vertical-align: middle;
            }
        }
    }

    &__form {
        display: flex;
        flex-direction: row;
        align-items: stretch;

        .input-group {
            flex: 1;
        }
    }
}

.btn-brl-none {
    button {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
}
.btn-br-none {
    button {
        border-radius: 0;
    }
}

.fj-index-checkbox__group {
    width: auto;
    margin-top: -7px;
    transform: translateX(-0.75rem);

    .input-group-prepend {
        &:first-child {
            .input-group-text {
                padding-left: calc(0.75rem - 1px);
                //width: 37px;
                background: transparent;
            }
        }
    }
}
</style>
