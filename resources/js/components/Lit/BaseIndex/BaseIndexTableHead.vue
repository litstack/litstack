<template>
    <thead class="lit-index-table-head">
        <tr>
            <th v-if="sortable"></th>
            <th v-if="!noSelect"><slot name="checkbox" /></th>
            <th
                v-for="(col, key) in cols"
                :key="key"
                :class="{
                    'text-right': col.text_right,
                    'text-center': col.text_center,
                }"
            >
                <div
                    @click="sortCol(col.value, col.sort_by, key)"
                    :class="{
                        'text-muted': key != activeCol && activeCol != null,
                        ['pointer']: col.sort_by ? true : false,
                    }"
                >
                    <span v-html="col.label" />
                    <span class="d-inline-block ml-2" v-if="key == activeCol">
                        <span v-html="sortIcon"> </span>
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
            required: true,
        },
        cols: {
            type: Array,
            required: true,
        },
        selectedItems: {
            type: Array,
            required: true,
        },
        noSelect: {
            type: Boolean,
            default() {
                return false;
            },
        },
    },
    data() {
        return {
            sort: null,
            activeCol: null,
        };
    },
    watch: {
        sort() {
            console.log('abc');
            this.$forceUpdate();
        },
    },
    computed: {
        sortIcon() {
            return this.sort == 'asc'
                ? `<i class="fas fa-sort-amount-up"></i>`
                : `<i class="fas fa-sort-amount-down"></i>`;
        },
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

            // TODO: remove this when crud table uses lit-index-table
            this.$bus.$emit('crudSort', sort);
            this.$emit('sort', sort);
        },
    },
};
</script>

<style lang="scss" scoped>
.pointer {
    cursor: pointer;
}
.lit-index-table-head {
    th {
        padding-bottom: 0.75rem;
        white-space: nowrap;
    }
    span {
        font-weight: 600;
    }
}
</style>
