<template>
    <b-td
        :class="{ reduce: col.reduce, 'fj-table-col': true, pointer: col.link }"
        :style="colWidth"
        @click="openLink(col.link)"
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
import { mapGetters } from 'vuex';
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
        ...mapGetters(['baseURL']),
        percentageColsCount() {
            let count = 0;
            for (let i = 0; i < this.cols.length; i++) {
                let col = this.cols[i];

                if (!col.reduce) {
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
        },
        openLink(link, item) {
            console.log(link, 'lol');
            if (!link) {
                return;
            }

            window.location.href = `${this.baseURL}${this._format(
                link,
                this.item
            )}`;
        }
    }
};
</script>
