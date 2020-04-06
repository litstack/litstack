<template>
    <div>
        <b-card no-body class="fj-index-table">
            <b-tabs card v-if="hasTabs" @activate-tab="newTab">
                <b-tab
                    :title="'title' in t ? t.title : t"
                    :active="t == tab"
                    v-for="(t, index) in tabs"
                    :key="index"
                    no-body/>
            </b-tabs>
            <div class="card-body">
                <slot name="header" v-bind:tab="tab"/>
                
                <fj-base-index-table-form>
                    <b-input-group>
                        <b-input-group-prepend is-text>
                            <fa-icon icon="search" />
                        </b-input-group-prepend>

                        <fj-base-index-table-search
                            :nameSingular="nameSingular"
                            :namePlural="namePlural"
                            @search="doSearch"/>
                        <template v-slot:append>
                            <fj-base-index-table-filter
                                :filter="filter"
                                @onFilterChange="filterChanged"/>
                            <fj-base-index-table-sort
                                :sortBy="sortBy"
                                :sortByDefault="sortByDefault"
                                @sort="sort"/>
                        </template>
                    </b-input-group>

                </fj-base-index-table-form>

                <fj-base-index-table-selected-items-actions
                    :actions="globalActions"
                    :items="items"
                    :selectedItems="selectedItems"
                    @reload="_loadItems()"/>

                <fj-base-index-table
                    :busy="isBusy"
                    :tableCols="tableCols"
                    :items="items"
                    :recordActions="recordActions"
                    @selectedItemsChanged="setSelectedItems"
                    @loadItems="_loadItems()"/>

                </fj-index-table-table>
            </div>
            <fj-base-index-table-index-indicator />
        </b-card>
        <div
            class="d-flex justify-content-center"
            v-if="numberOfPages > 1">

            <b-pagination-nav
                class="mt-4"
                :link-gen="linkGen"
                :number-of-pages="numberOfPages"
                @change="goToPage">
            </b-pagination-nav>

        </div>
    </div>
</template>

<script>
import TableModel from '@fj-js/eloquent/table.model';

export default {
    name: 'IndexTable',
    props: {
        tabs: {
            required: false,
            type: Array,
            default() {
                return [];
            }
        },
        cols: {
            required: true,
            type: Array
        },
        numberOfPages: {
            type: Number,
            default: () => {
                return 1;
            }
        },
        searchKeys: {
            type: Array,
            default() {
                return ['name']
            },
        },
        recordActions: {
            type: Array,
            default: () => {
                return [];
            }
        },
        globalActions: {
            type: Array,
            default: () => {
                return [];
            }
        },
        items: {
            type: [Object, Array],
            required: true
        },
        loadItems: {
            type: Function,
            require: true
        },
        sortByDefault: {
            type: Boolean
        },
        nameSingular: {
            type: String
        },
        namePlural: {
            type: String
        },
        filter: {
            type: Object,
            default() {
                return {}
            }
        },
        sortBy: {
            type: Object,
            default() {
                return {}
            }
        },
        sortByDefault: {
            type: String
        }
    },
    data() {
        return {
            isBusy: true,
            tab: null,

            tableCols: {},
            selectedItems: [],

            search: '',
            sort_by_key: '',

            filter_scope: null,

            currentPage: 1
        };
    },

    watch: {
        tabs() {
            if(!this.hasTabs) {
                return
            }
            if(!this.tabs.includes(this.tab)) {
                this.tab = this.tabs[0]
            }
        }
    },
    beforeMount() {
        this.setTableCols();

        if(this.hasTabs) {
            this.tab = this.tabs[0]
        }
        
        this.sort_by_key = this.sortByDefault || null;

        this.$on('reload', this._loadItems)

        this._loadItems();
    },
    computed: {
        perPage() {
            return 20;
        },
        hasTabs() {
            return this.tabs.length > 0;
        }
    },
    methods: {
        newTab(index) {
            this.tab = this.tabs[index]
            this._loadItems()
        },
        filterChanged(filter) {
            this.filter_scope = filter
            this._loadItems()
        },
        sort(key) {
            this.sort_by_key = key
            this._loadItems()
        },
        setSelectedItems(selectedItems) {
            this.selectedItems = selectedItems
        },
        async _loadItems() {
            this.isBusy = true;

            let payload = {
                tab: this.tab,
                page: this.currentPage,
                perPage: this.perPage,
                search: this.search,
                sort_by: this.sort_by_key,
                filter: this.filter_scope,
                searchKeys: this.searchKeys
            };

            await this.loadItems(payload)
            try {

            } catch (e) {
                this.$bus.$emit('error', e);
            }

            this.isBusy = false;
        },
        doSearch(query) {
            this.search = query
            this._loadItems();
        },
        goToPage(page) {
            this.currentPage = page;
            this._loadItems();
        },
        linkGen(pageNum) {
            return { path: `#${pageNum}` };
        },

        setTableCols() {
            this.tableCols = [];

            // TODO: make this work
            //if(this.recordActions.length > 0) {
                // prepend checkbox col if table has recordActions
                this.tableCols.push({
                    key: 'check',
                    label: 'Check'
                });
            //}

            for (let i = 0; i < this.cols.length; i++) {
                let col = this.cols[i];

                if (typeof col == typeof '') {
                    col = { key: col, title: col.capitalize() };
                }

                this.tableCols.push(col);
            }
        },
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';
.fj-index-table {
    .nav-tabs {
        margin-left: 0;
        margin-right: 0;
    }

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
