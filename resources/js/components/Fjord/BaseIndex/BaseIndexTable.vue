<template>
    <b-table-simple :aria-busy="busy">
        <fj-colgroup :icons="['check']" :cols="tableCols" />

        <fj-crud-index-table-head
            :tableCols="tableCols"
            :hasRecordActions="hasRecordActions"
            :selectedItems="selectedItems">
            <b-checkbox
                slot="checkbox"
                class="float-left"
                v-model="selectedAll"
                :indeterminate.sync="indeterminate"
                @change="changeSelectedItems"
            />
        </fj-crud-index-table-head>

        <tbody>
            <tr
                role="row"
                class="b-table-busy-slot"
                v-if="busy"
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
                            @reload="_loadItems"
                        />
                    </td>
                </tr>
            </template>
        </tbody>
    </b-table-simple>
</template>
<script>

export default {
    name: "BaseIndexTable",
    props: {
        tableCols: {
            required: true,
            type: Array
        },
        items: {
            type: Array,
            required: true
        },
        busy: {
            type: Boolean,
            required: true
        },
        recordActions: {
            type: Array,
            default() {
                return []
            }
        },
    },
    data() {
        return {
            selectedAll: false,
            indeterminate: false,
            selectedItems: []
        }
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
        hasRecordActions() {
            return this.recordActions.length > 0;
        },
    },
    methods: {
        _loadItems() {
            this.$emit('loadItems')
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
    },
}
</script>
