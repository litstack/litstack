<template>
    <div>
        <b-row>
            <b-col>
                <b-card>
                    <div class="fj-crud-index-table">
                        <fj-crud-index-table-form />

                        <fj-crud-index-table-selected-items-actions
                            :items="items"
                            :selectedItems="selectedItems"
                        />

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
                                    :indeterminate.sync="
                                        indeterminateSelectedItems
                                    "
                                    @change="changeSelectedItems"
                                />
                            </fj-crud-index-table-head>

                            <tbody>
                                <tr
                                    role="row"
                                    class="b-table-busy-slot"
                                    v-if="isBusy"
                                >
                                    <td
                                        :colspan="tableCols.length"
                                        role="cell"
                                        align="center"
                                    >
                                        <fj-base-spinner class="text-center" />
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
                                        <template
                                            v-for="(col, col_key) in tableCols"
                                        >
                                            <td v-if="col.key == 'check'">
                                                <b-checkbox
                                                    v-model="selectedItems"
                                                    :value="item.id"
                                                />
                                            </td>
                                            <td
                                                v-else-if="
                                                    col.component !== undefined
                                                "
                                                class="pointer"
                                            >
                                                <component
                                                    :is="col.component"
                                                    :item="item"
                                                    :col="col"
                                                />
                                            </td>
                                            <td
                                                v-else
                                                @click="openItem(item)"
                                                class="pointer"
                                            >
                                                <fj-table-col
                                                    :item="item"
                                                    :col="col"
                                                />
                                            </td>
                                        </template>
                                        <td v-if="hasRecordActions">
                                            <component
                                                v-for="(component,
                                                key) in recordActions"
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
                    <fj-crud-index-table-index-indicator :items="items" />
                </b-card>
            </b-col>
        </b-row>
        <div
            class="d-flex justify-content-center"
            v-if="form.config.index.per_page !== undefined"
        >
            <b-pagination-nav
                class="mt-4"
                :link-gen="linkGen"
                :number-of-pages="number_of_pages"
                @change="goToPage"
            ></b-pagination-nav>
        </div>
    </div>
</template>

<script>
import TableModel from '@fj-js/eloquent/table.model';
import { mapGetters } from 'vuex';
export default {
    name: 'CrudIndexTable',
    props: {
        cols: {
            required: true,
            type: Array
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
            selectedItems: [],
            indeterminateSelectedItems: false,

            search: '',
            sort_by_key: '',

            filter_scope: null,
            selectedAll: false,

            page: 1,
            number_of_pages: null,
            total: null
        };
    },
    watch: {
        search(val) {
            setTimeout(() => {
                if (this.search == val) {
                    this.loadItems();
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

        this.sort_by_key = this.form.config.index.sort_by_default || null;

        this.loadItems();

        this.$bus.$on('crudSearch', val => {
            this.search = val;
        });
        this.$bus.$on('crudSort', val => {
            this.sort_by_key = val;
            this.loadItems();
        });
        this.$bus.$on('crudFilter', key => {
            this.filter_scope = key;
            this.loadItems();
        });

        this.$bus.$on('reloadCrudIndex', () => {
            this.loadItems();
        });
        this.$bus.$on('unselectCrudIndex', () => {
            this.selectedItems = [];
        });
        this.$bus.$on('openItem', item => {
            this.openItem(item);
        });
    },
    computed: {
        ...mapGetters(['form']),
        hasRecordActions() {
            return this.recordActions.length > 0;
        },
        perPage() {
            return this.form.config.index.per_page || 0;
        }
    },
    methods: {
        async loadItems() {
            this.isBusy = true;

            let payload = {
                page: this.page,
                perPage: this.perPage,
                search: this.search,
                sort_by: this.sort_by_key,
                filter: this.filter_scope,
                eagerLoad: this.form.config.index.load || []
            };

            let response = await axios.post(
                `${this.form.config.names.table}/index`,
                payload
            );

            this.total = response.data.count;

            let items = [];
            for (let i = 0; i < response.data.items.length; i++) {
                items.push(new TableModel(response.data.items[i]));
            }
            this.items = items;
            this.number_of_pages = Math.ceil(
                response.data.count / this.perPage
            );

            this.isBusy = false;
        },
        goToPage(page) {
            this.page = page;
            this.loadItems();
        },
        linkGen(pageNum) {
            return { path: `#${pageNum}` };
        },
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
        sortCol(value) {
            this.sortBy(value);
        },
        openItem(item) {
            window.location.href =
                `${this.form.config.names.table}/${item.id}` +
                ('route' in this.form.config.index
                    ? this.form.config.index.route
                    : '/edit');
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';
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
                > div {
                    white-space: nowrap;
                }

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
    &__index-indicator {
        position: absolute;
        margin-top: -10px;
        right: 20px;
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
