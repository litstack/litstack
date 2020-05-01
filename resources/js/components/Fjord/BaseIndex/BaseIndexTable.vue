<template>
    <b-table-simple
        :aria-busy="busy"
        hover
        borderless
        striped
        v-bind:small="small"
    >
        <fj-base-index-table-head
            :cols="cols"
            :selectedItems="selectedItems"
            @sort="sort"
            v-if="!noHead"
        >
            <b-checkbox
                slot="checkbox"
                class="float-left"
                v-model="selectedAll"
                :indeterminate.sync="indeterminate"
                @change="changeSelectedItems"
            />
        </fj-base-index-table-head>

        <tbody>
            <tr role="row" class="b-table-busy-slot" v-if="busy">
                <td :colspan="colspan" role="cell" align="center">
                    <fj-spinner class="text-center" />
                </td>
            </tr>
            <template v-else>
                <tr
                    v-for="(item, key) in items"
                    :key="key"
                    :class="isItemSelected(item) ? 'table-primary' : ''"
                >
                    <td class="reduce fj-table-select">
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
                                @click="selected(item)"
                            ></label>
                        </div>
                        <b-checkbox
                            v-else
                            :checked="isItemSelected(item)"
                            @input="selected(item)"
                        />
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
            </template>
        </tbody>
    </b-table-simple>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
    name: 'BaseIndexTable',
    props: {
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
    data() {
        return {
            selectedAll: false,
            indeterminate: false
        };
    },
    watch: {
        selectedItems(val) {
            this.$emit('selectedItemsChanged', val);
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
            return this.cols.length + 1;
        }
    },
    methods: {
        selected(item) {
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
