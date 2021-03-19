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
                    @click="sortCol(col.sort_by)"
                    :class="{
                        'text-muted': col.sort_by != sortByColumn && sortByColumn != null,
                        ['pointer']: col.sort_by ? true : false,
                    }"
                >
                    <span v-html="col.label" />
                    <span class="d-inline-block ml-2" v-if="col.sort_by == sortByColumn && sortByColumn != null">
                        <span v-html="sortIcon"/>
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
        sortByColumn: {
            type: String,
        },
        sortByDirection: {
            type: String,
        },
        noSelect: {
            type: Boolean,
            default() {
                return false;
            },
        },
    },
    watch: {
        sortByColumn() {
            console.log('abc');
            this.$forceUpdate();
        },
        sortByDirection() {
            console.log('abc');
            this.$forceUpdate();
        },
    },
    computed: {
        sortIcon() {
            return this.sortByDirection == 'asc'
                ? `<i class="fas fa-sort-amount-up"></i>`
                : `<i class="fas fa-sort-amount-down"></i>`;
        },
    },
    methods: {
        sortCol(column) {
            if (!column) {
                return;
            }

            let direction = '';

            switch (this.sortByDirection) {
                case 'asc':
                    direction = null;
                    break;
                case 'desc':
                    direction = 'asc';
                    break;
                default:
                    direction = 'asc';
                    break;
            }

            if(this.sortByColumn != column) {
                direction = 'desc';
            }

            if(direction == null) { 
                column = null;
            }

            this.$emit('sort', {column, direction});
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
