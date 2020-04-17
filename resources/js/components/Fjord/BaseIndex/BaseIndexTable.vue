<template>
    <b-table-simple :aria-busy="busy" hover>
        <fj-base-index-table-head
            :cols="cols"
            :hasRecordActions="hasRecordActions"
            :selectedItems="selectedItems"
            @sort="sort"
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
                <td :colspan="cols.length" role="cell" align="center">
                    <fj-base-spinner class="text-center" />
                </td>
            </tr>
            <template v-else>
                <tr
                    v-for="(item, key) in items"
                    :key="key"
                    :class="
                        selectedItems.includes(item.id) ? 'table-primary' : ''
                    "
                >
                    <td class="reduce">
                        <b-checkbox v-model="selectedItems" :value="item.id" />
                    </td>
                    <fj-table-col
                        v-for="(col, col_key) in cols"
                        :col="col"
                        :key="col_key"
                        :item="item"
                        :cols="cols"
                    />
                    <!--
                    <template v-for="(col, col_key) in tableCols">
                        <td
                            v-else
                            @click="openLink(col.link, item)"
                            :class="col.link ? 'pointer' : ''"
                        >
                            <fj-table-col :item="item" :col="col" />
                        </td>
                    </template>
                    -->
                    <!--
                    <td v-if="hasRecordActions">
                        <component
                            v-for="(component, key) in recordActions"
                            :key="key"
                            :is="component"
                            :item="item"
                            @reload="_loadItems"
                        />
                    </td>
                    -->
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
        busy: {
            type: Boolean,
            required: true
        },
        recordActions: {
            type: Array,
            default() {
                return [];
            }
        }
    },
    data() {
        return {
            selectedAll: false,
            indeterminate: false,
            selectedItems: []
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
        hasRecordActions() {
            return this.recordActions.length > 0;
        }
    },
    methods: {
        sort(sort) {
            this.$emit('sort', sort);
        },
        _loadItems() {
            this.$emit('loadItems');
        },
        changeSelectedItems(val) {
            if (val) {
                this.selectedItems = this.items.map(item => {
                    return item.id;
                });
            } else {
                this.selectedItems = [];
            }
        }
    }
};
</script>
