<template>
    <b-table-simple
        :aria-busy="busy"
        hover
        borderless
        striped
        responsive
        v-bind:small="small"
    >
        <fj-base-index-table-head
            :cols="cols"
            :sortable="sortable"
            :selectedItems="selectedItems"
            @sort="sort"
            v-if="!noHead"
            :no-select="noSelect"
        >
            <b-checkbox
                ref="headerCheckbox"
                v-if="!radio && indeterminate"
                slot="checkbox"
                class="float-left"
                indeterminate
                @change="toggleSelectAll"
            />
            <b-checkbox
                ref="headerCheckbox"
                v-else="!radio && !indeterminate"
                slot="checkbox"
                class="float-left"
                v-model="selectedAll"
                @change="toggleSelectAll"
            />
        </fj-base-index-table-head>

        <draggable
            v-model="sortableItems"
            @end="newOrder"
            handle=".fj-draggable__dragbar"
            tag="tbody"
        >
            <tr role="row" class="b-table-busy-slot" v-if="busy">
                <td :colspan="colspan" role="cell" align="center">
                    <fj-spinner class="text-center" />
                </td>
            </tr>

            <tr
                v-else
                v-for="(item, key) in sortableItems"
                :key="key"
                :class="isItemSelected(item) ? 'table-primary' : ''"
            >
                <td v-if="sortable">
                    <fa-icon
                        icon="grip-horizontal"
                        class="text-secondary fj-draggable__dragbar"
                    />
                </td>
                <td class="small fj-table-select" v-if="!noSelect">
                    <div class="custom-control custom-radio" v-if="radio">
                        <input
                            type="radio"
                            autocomplete="off"
                            class="custom-control-input pointer-events-none"
                            value=""
                            :checked="isItemSelected(item)"
                        />
                        <label
                            class="custom-control-label"
                            @click="select(item)"
                        ></label>
                    </div>
                    <a href="#" v-else @click.prevent="toggleSelect(item)">
                        <b-checkbox :checked="isItemSelected(item)" />
                    </a>
                </td>
                <fj-table-col
                    v-for="(col, col_key) in cols"
                    :col="col"
                    :key="col_key"
                    :item="item"
                    :cols="cols"
                    @reload="$emit('loadItems')"
                />
            </tr>
        </draggable>
    </b-table-simple>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
    name: 'BaseIndexTable',
    props: {
        sortable: {
            type: Boolean,
            required: true
        },
        cols: {
            required: true,
            type: Array
        },
        items: {
            type: [Object, Array],
            required: true
        },
        radio: {
            type: Boolean,
            default() {
                return false;
            }
        },
        noSelect: {
            type: Boolean,
            default() {
                return false;
            }
        },
        noHead: {
            type: Boolean,
            default() {
                return false;
            }
        },
        small: {
            type: Boolean,
            default() {
                return false;
            }
        },
        busy: {
            type: Boolean,
            required: true
        },
        selectedItems: {
            type: Array,
            required: true
        }
    },
    beforeMount() {
        this.sortableItems = this.items;
    },
    data() {
        return {
            selectedAll: false,
            indeterminate: false,
            sortableItems: []
        };
    },
    watch: {
        /**
         * Reset sortableItems when items array has changed.
         */
        items(val) {
            this.sortableItems = this.items;
            this.$forceUpdate();
        },
        /**
         * Watch selected items to set indeterminate for table header checkbox.
         */
        selectedItems(val) {
            if (val.length == this.items.length) {
                this.selectedAll = true;
                this.indeterminate = false;
                return;
            }
            this.selectedAll = false;
            this.indeterminate = val.length > 0 ? true : false;
        }
    },
    computed: {
        ...mapGetters(['config']),
        colspan() {
            // Adding one for the checkbox field.
            let span = this.cols.length;
            if (this.sortable) {
                span++;
            }
            if (!this.noSelect) {
                span++;
            }
            return span;
        }
    },
    methods: {
        newOrder(items) {
            this.$emit('sorted', this.sortableItems);
        },
        toggleSelectAll() {
            if (this.allItemsAreSelected()) {
                for (let i in this.items) {
                    let item = this.items[i];
                    if (!this.isItemSelected(item)) {
                        continue;
                    }
                    this.$emit('unselect', item);
                }
            } else {
                for (let i in this.items) {
                    let item = this.items[i];
                    if (this.isItemSelected(item)) {
                        continue;
                    }
                    this.$emit('select', item);
                }
            }
        },
        allItemsAreSelected() {
            for (let i in this.items) {
                if (!this.isItemSelected(this.items[i])) {
                    return false;
                }
            }
            return true;
        },
        select(item) {
            if (this.isItemSelected(item)) {
                return;
            }
            this.$emit('select', item);
        },
        toggleSelect(item) {
            if (this.isItemSelected(item)) {
                if (!this.radio) {
                    this.$emit('unselect', item);
                }
            } else {
                this.$emit('select', item);
            }
            this.$forceUpdate();
        },
        sort(sort) {
            this.$emit('sort', sort);
        },
        _loadItems() {
            this.$emit('loadItems');
        },
        isItemSelected(item) {
            return this.selectedItems.find(model => {
                return model ? model.id == item.id : false;
            })
                ? true
                : false;
        }
    }
};
</script>

<style lang="scss">
.b-table-busy-slot {
    &:hover {
        background: transparent;
    }
}
</style>
