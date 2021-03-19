<template>
    <div>
        <b-card
            no-body
            class="lit-index-table"
            :style="noCard ? 'box-shadow: none;' : ''"
        >
            <b-tabs card v-if="hasTabs" @activate-tab="newTab">
                <b-tab
                    :title="'title' in t ? t.title : t"
                    :active="t == tab"
                    v-for="(t, index) in tabs"
                    :key="index"
                    no-body
                />
            </b-tabs>
            <div class="card-body">
                <slot name="header" v-bind:tab="tab" />

                <lit-base-index-table-form
                    v-if="
                        searchKeys.length != 0 ||
                        !_.isEmpty(sortBy) ||
                        !_.isEmpty(filter)
                    "
                >
                    <b-input-group>
                        <b-input-group-prepend is-text>
                            <lit-fa-icon icon="search" />
                        </b-input-group-prepend>

                        <lit-base-index-table-search
                            :name-singular="nameSingular"
                            :name-plural="namePlural"
                            :filter-scopes="filter_scopes"
                            @search="doSearch"
                            :style="
                                filter_scopes.length > 0
                                    ? 'border-right-color: transparent'
                                    : ''
                            "
                        />

                        <b-input-group-append
                            is-text
                            class="lit-index-table__active-filters"
                            variant="primary"
                            v-if="filter_scopes.length > 0"
                            style="background: transparent; border-left: none;"
                        >
                            <lit-base-index-filter-tag
                                v-for="(scope, key) in filter_scopes"
                                :key="key"
                                :filter="scope"
                                @remove="removeFilter"
                            />
                        </b-input-group-append>
                    </b-input-group>
                    <lit-base-index-table-filter
                        :filter="filter"
                        :filter-scopes="filter_scopes"
                        v-if="hasFilter"
                        @addFilter="addFilter"
                        @removeFilter="removeFilter"
                        @resetFilter="resetFilter"
                    />

                    <lit-base-index-table-sort
                        v-if="hasSort"
                        :sort-by="sortBy"
                        :sort-by-default="sortByDefault"
                        :sort-by-key="sort_by_key"
                        @sort="sort"
                    />
                </lit-base-index-table-form>

                <lit-base-index-table-selected-items-actions
                    :actions="actions"
                    :items="items"
                    :selectedItems="selectedItems"
                    @reload="executedActions"
                    v-if="!!actions.length"
                />

                <lit-base-index-table
                    ref="table"
                    :namePlural="namePlural"
                    :sortable="canSort"
                    :busy="isBusy"
                    :cols="cols"
                    :items="items"
                    :radio="radio"
                    :no-head="noHead"
                    :no-select="noSelect"
                    :small="small"
                    :selectedItems="selectedItems"
                    :sort-by-column="sortByColumn"
                    :sort-by-direction="sortByDirection"
                    @select="select"
                    @unselect="unselect"
                    @sort="sort"
                    @loadItems="_loadItems()"
                    @_sorted="sorted"
                    :class="{ paginated: total > items.length }"
                    v-on="$listeners"
                    v-bind="$attrs"
                />

                <lit-base-index-table-index-indicator
                    :per-page="perPage"
                    :total="total"
                    :items="items"
                    :current-page="currentPage"
                    v-if="total > items.length"
                />

                <div
                    class="d-flex justify-content-center lit-index-table-pagination"
                    v-if="numberOfPages > 1"
                >
                    <b-pagination-nav
                        class="mt-2"
                        :link-gen="linkGen"
                        v-model="currentPage"
                        :number-of-pages="numberOfPages"
                        @change="goToPage"
                    />
                </div>
            </div>
        </b-card>
    </div>
</template>

<script>
export default {
    name: 'IndexTable',
    props: {
        sortable: {
            type: [Boolean, String],
            default() {
                return false;
            },
        },
        orderColumn: {
            type: String,
            default() {
                return 'order_column';
            },
        },
        tabs: {
            required: false,
            type: Array,
            default() {
                return [];
            },
        },
        radio: {
            type: Boolean,
            default() {
                return false;
            },
        },
        small: {
            type: Boolean,
            default() {
                return false;
            },
        },
        noSelect: {
            type: Boolean,
            default() {
                return false;
            },
        },
        noHead: {
            type: Boolean,
            default() {
                return false;
            },
        },
        noCard: {
            type: Boolean,
            default() {
                return false;
            },
        },
        cols: {
            required: true,
            type: Array,
        },
        searchKeys: {
            type: Array,
            default() {
                return [];
            },
        },
        actions: {
            type: Array,
            default: () => {
                return [];
            },
        },
        items: {
            type: [Object, Array],
            required: true,
        },
        loadItems: {
            type: Function,
            default() {
                null;
            },
        },
        nameSingular: {
            type: String,
        },
        namePlural: {
            type: String,
        },
        perPage: {
            type: Number,
            default() {
                return 20;
            },
        },
        filter: {
            type: [Object, Array],
            default() {
                return {};
            },
        },
        defaultFilter: {
            type: [Object, Array],
            default() {
                return [];
            },
        },
        sortBy: {
            type: Object,
            default() {
                return {};
            },
        },
        sortByDefault: {
            type: String,
            default() {
                return 'id.desc';
            },
        },
        selected: {
            type: Array,
            default() {
                return [];
            },
        },
    },
    data() {
        return {
            isBusy: false,
            tab: null,
            search: '',
            sort_by_key: '',
            filter_scopes: [],
            currentPage: 1,
            numberOfPages: 1,
            selectedItems: [],
            total: 0,
        };
    },
    watch: {
        tabs() {
            if (!this.hasTabs) {
                return;
            }
            if (!this.tabs.includes(this.tab)) {
                this.tab = this.tabs[0];
            }
        },
    },
    beforeMount() {
        if (this.defaultFilter) {
            for (let i = 0; i < this.defaultFilter.length; i++) {
                this.addFilter(this.defaultFilter[i]);
            }
        }

        if (this.hasTabs) {
            this.tab = this.tabs[0];
        }
        this.selectedItems = _.clone(this.selected);

        this.sort_by_key = this.sortByDefault || null;

        Lit.bus.$on('reload', this._loadItems);

        this.$on('reload', this._loadItems);
        this.$on('refreshSelected', () => {
            this.selectedItems = _.clone(this.selected);
        });
        this.$on('refresh', () => {
            this.$refs.table.$forceUpdate();
        });

        this._loadItems();
    },
    computed: {
        sortByColumn() {
            if(!this.sort_by_key){
                return;
            }
            return _(this.sort_by_key.split('.'))
                .tap(a => a.pop())
                .value()
                .join('.');
        },
        sortByDirection() {
            if(!this.sort_by_key){
                return;
            }
            return _.last(this.sort_by_key.split('.'));
        },
        canSort() {
            if (!this.sortable) {
                return false;
            }
            if (this.sortable == 'force') {
                return true;
            }
            return (
                this.sort_by_key == `${this.orderColumn}.asc` ||
                this.sort_by_key == `${this.orderColumn}.desc`
            );
        },
        hasFilter() {
            return Object.keys(this.filter).length !== 0;
        },
        hasSort() {
            return Object.keys(this.sortBy).length !== 0;
        },
        hasTabs() {
            return this.tabs.length > 0;
        },
    },
    methods: {
        sorted(sortedItems) {
            let ids = {};
            let start = this.currentPage * this.perPage - this.perPage;
            if (this.sort_by_key.endsWith('desc')) {
                start = this.total - start - 1;
            }
            for (let i in sortedItems) {
                if (this.sort_by_key.endsWith('desc')) {
                    ids[start - parseInt(i)] = sortedItems[i].id;
                } else {
                    ids[start + parseInt(i)] = sortedItems[i].id;
                }
            }
            this.$emit('sorted', { sortedItems, ids });
        },
        newTab(index) {
            this.tab = this.tabs[index];
            this.resetCurrentPage();
            this._loadItems();
        },
        addFilter(filter) {
            if (this.filter_scopes.includes(filter)) {
                return;
            }

            if (typeof filter === 'object') {
                this.addCustomFilter(filter);
            } else {
                this.addFilterScope(filter);
            }
            this.resetCurrentPage();
            this._loadItems();
        },
        addCustomFilter(newFilter) {
            let match = false;
            this.filter_scopes = _.map(this.filter_scopes, function (filter) {
                if (typeof scope === 'object') {
                    return filter;
                }
                if (filter.filter != newFilter.filter) {
                    return filter;
                }

                filter.values = {
                    ...filter.values,
                    ...newFilter.values,
                };
                filter.attributeNames = {
                    ...filter.attributeNames,
                    ...newFilter.attributeNames,
                };

                match = true;

                return filter;
            });

            if (match) {
                return;
            }

            this.filter_scopes.push(newFilter);
        },
        addFilterScope(scope) {
            this.filter_scopes.push(scope);
        },
        removeFilter(filter) {
            console.log(
                'remove',
                filter,
                _.without(this.filter_scopes, filter)
            );
            this.filter_scopes = _.without(this.filter_scopes, filter);
            this.resetCurrentPage();
            this._loadItems();
        },
        resetFilter() {
            this.filter_scopes = [];
        },
         sort({column, direction}) {
            if(!column) {
                this.sort_by_key = '';
            } else {
                this.sort_by_key = `${column}.${direction}`;
            }
            this._loadItems();
        },
        unselect(item) {
            for (let i in this.selectedItems) {
                if (this.selectedItems[i].id == item.id) {
                    this.selectedItems.splice(i, 1);
                }
            }
        },
        select(item) {
            if (this.radio) {
                this.selectedItems = [];
                this.selectedItems.push(item);
            } else {
                this.selectedItems.push(item);
            }
        },
        isItemSelected(item) {
            return this.selectedItems.find((model) => {
                return model ? model.id == item.id : false;
            })
                ? true
                : false;
        },
        executedActions() {
            this.selectedItems = [];
            this._loadItems();
        },
        async _loadItems() {
            if (!this.loadItems) {
                return;
            }
            this.isBusy = true;

            let payload = {
                tab: this.tab,
                page: this.currentPage,
                perPage: this.perPage,
                search: this.search,
                sort_by: this.sort_by_key,
                filter: this.filter_scopes,
                searchKeys: this.searchKeys,
            };

            let response = await this.loadItems(payload);

            this.$refs.table.$emit('loaded');

            this.isBusy = false;

            if (!response) return;
            if (!'data' in response) return;
            if (!'count' in response.data) return;

            this.numberOfPages = Math.ceil(response.data.count / this.perPage);
            this.total = response.data.count;
        },
        resetCurrentPage() {
            this.currentPage = 1;
        },
        doSearch(query) {
            this.search = query;
            this.resetCurrentPage();
            this._loadItems();
        },
        goToPage(page) {
            this.currentPage = page;
            this._loadItems();
        },
        linkGen(pageNum) {
            return { path: `#${pageNum}` };
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
.lit-index-table {
    &__active-filters {
        .input-group-text {
            background: transparent;
            border-left: white 1px solid;
            padding-right: 0.3125rem;
        }
    }
    .nav-tabs {
        margin-left: 0;
        margin-right: 0;
    }

    .table-responsive {
        width: auto;
        margin-left: -$card-spacer-x;
        margin-right: -$card-spacer-x;
        min-width: calc(100% + #{2 * $card-spacer-x});
        overflow-x: initial;
        @media (max-width: map-get($grid-breakpoints, $nav-breakpoint-mobile)) {
            margin-left: -$page-margin-sm;
            margin-right: -$page-margin-sm;
            min-width: unset;
        }
    }

    .paginated {
        table.b-table {
            tbody {
                tr:last-child {
                    td {
                        border-bottom: 1px solid $gray-300;
                    }
                }
            }
        }
    }

    table.b-table {
        &[aria-busy='true'] {
            opacity: 0.6;
        }

        thead th {
            border-top: none;
            border-bottom: 1px solid $gray-300;

            &:first-child {
                padding-left: $card-spacer-x;
                @media (max-width: map-get($grid-breakpoints, $nav-breakpoint-mobile)) {
                    padding-left: $page-margin-sm;
                }
            }

            &:last-child {
                padding-right: $card-spacer-x;
                @media (max-width: map-get($grid-breakpoints, $nav-breakpoint-mobile)) {
                    padding-right: $page-margin-sm;
                }
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
                    padding-left: $card-spacer-x;
                    @media (max-width: map-get($grid-breakpoints, $nav-breakpoint-mobile)) {
                        padding-left: $page-margin-sm;
                    }
                }

                &:last-child {
                    padding-right: $card-spacer-x;
                    @media (max-width: map-get($grid-breakpoints, $nav-breakpoint-mobile)) {
                        padding-right: $page-margin-sm;
                    }
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

    &__litorm {
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

.lit-index-checkbox__group {
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
.lit-index-table-pagination {
    margin-top: -8px;
}
</style>
