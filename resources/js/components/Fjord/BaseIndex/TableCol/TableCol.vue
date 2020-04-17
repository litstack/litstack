<template>
    <b-td
        :class="{ reduce: col.component !== undefined, 'fj-table-col': true }"
        :style="colWidth"
    >
        <component
            v-if="col.component !== undefined"
            :is="col.component.name"
            :item="item"
            :col="col"
            v-bind="getColComponentProps()"
        />
        <div v-else v-html="getColValue(col.value)" />
    </b-td>
</template>

<script>
export default {
    name: 'TableCol',
    props: {
        item: {
            required: true,
            type: Object
        },
        col: {
            required: true,
            type: Object
        },
        cols: {
            required: true,
            type: Array
        }
    },
    computed: {
        percentageColsCount() {
            let count = 0;
            for (let i = 0; i < this.cols.length; i++) {
                let col = this.cols[i];

                if (col.component !== undefined) {
                    continue;
                }

                count++;
            }
            return count;
        },
        colWidth() {
            if (this.col.component !== undefined) {
                return;
            }
            return 'width: ' + 100 / this.percentageColsCount + '%;';
        }
    },
    methods: {
        getColValue(col) {
            // Regex for has {value} pattern.
            if (/{(.*?)}/.test(col)) {
                return this._format(col, this.item);
            } else if (this.item[col] !== undefined) {
                return this.item[col];
            }
            return col;
        },
        getColComponentProps() {
            if (!this.col.component.props) {
                return {};
            }

            let compiled = {};

            for (let name in this.col.component.props) {
                let prop = this.col.component.props[name];
                compiled[name] = this.getColValue(prop);
            }

            return compiled;
        }
    }
};
</script>

<style scoped>
.fj-table-col {
    vertical-align: middle;
}
</style>
