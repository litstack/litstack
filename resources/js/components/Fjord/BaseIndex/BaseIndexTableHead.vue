<template>
    <thead>
        <tr>
            <th v-if="sortable"></th>
            <th v-if="!noSelect"><slot name="checkbox" /></th>
            <th v-for="(col, key) in cols" :key="key">
                <div
                    @click="sortCol(col.value, col.sort_by, key)"
                    :class="{
                        'text-muted': key != activeCol && activeCol != null,
                        ['pointer']: col.sort_by ? true : false
                    }"
                >
                    <span v-html="col.label" />
                    <span class="d-inline-block ml-2" v-if="key == activeCol">
                        <fa-icon
                            icon="sort-amount-down-alt"
                            v-if="sort == 'asc'"
                        />
                        <fa-icon
                            icon="sort-amount-down"
                            v-if="sort == 'desc'"
                        />
                    </span>
                </div>
            </th>
        </tr>
    </thead>
</template>

<script>
export default {
    name: 'BaseIndexTableHead',
    props: {
        sortable: {
            type: Boolean,
            required: true
        },
        cols: {
            type: Array,
            required: true
        },
        selectedItems: {
            type: Array,
            required: true
        },
        noSelect: {
            type: Boolean,
            default() {
                return false;
            }
        }
    },
    data() {
        return {
            sort: null,
            activeCol: null
        };
    },
    methods: {
        sortCol(value, sort_by, index) {
            if (!sort_by) {
                return;
            }
            if (this.sort == null) {
                this.sort = 'asc';
            }
            this.activeCol = index;

            let sort = sort_by;

            if (!sort_by) {
                sort = value
                    .replace('{', '')
                    .replace('}', '')
                    .concat(`.${this.sort}`);
            }

            switch (this.sort) {
                case 'asc':
                    this.sort = 'desc';
                    break;
                case 'desc':
                    this.sort = 'asc';
                    break;
            }

            sort = `${sort}.${this.sort}`;

            // TODO: remove this when crud table uses fj-index-table
            this.$bus.$emit('crudSort', sort);
            this.$emit('sort', sort);
        }
    }
};
</script>

<style lang="css" scoped>
.pointer {
    cursor: pointer;
}
</style>
