<template>
    <thead>
        <tr>
            <th><slot name="checkbox" /></th>
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
            <th v-if="hasRecordActions"></th>
        </tr>
    </thead>
</template>

<script>
export default {
    name: 'BaseIndexTableHead',
    props: {
        cols: {
            type: Array,
            required: true
        },
        hasRecordActions: {
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
