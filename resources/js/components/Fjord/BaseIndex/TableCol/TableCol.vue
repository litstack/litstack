<template>
    <b-td
        :class="{
            small: isSmall(col),
            'fj-table-col': true,
            pointer: col.link
        }"
        :style="colWidth"
        @click="openLink(col.link)"
    >
        <component
            v-if="col.component !== undefined"
            :is="col.component"
            :item="item"
            :col="col"
            @reload="reload"
            v-bind="getColComponentProps()"
        />
        <div v-else v-html="value" />
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
    data() {
        return {
            value: ''
        };
    },
    beforeMount() {
        this.setValue();

        // Can be called from parents to refresh value.
        this.$on('refresh', this.setValue);

        Fjord.bus.$on('languageChanged', this.setValue);
    },
    computed: {
        ...mapGetters(['baseURL']),
        percentageColsCount() {
            let count = 0;
            for (let i = 0; i < this.cols.length; i++) {
                let col = this.cols[i];

                if (this.isSmall(col)) {
                    continue;
                }

                count++;
            }
            return count;
        },
        colWidth() {
            if (this.isSmall(this.col)) {
                return;
            }
            let percentage = 100;
            if (this.percentageColsCount > 0) {
                percentage = 100 / this.percentageColsCount;
            }
            return 'width: ' + percentage + '%;';
        }
    },
    methods: {
        setValue() {
            this.value = this.getColValue(this.col.value);
        },
        reload() {
            this.$emit('reload');
        },
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
            if (!this.col.component) {
                return {};
            }

            let compiled = {};

            for (let name in this.col.props) {
                let prop = this.col.props[name];
                compiled[name] = this.getColValue(prop);
            }

            return compiled;
        },
        openLink(link, item) {
            if (!link) {
                return;
            }

            window.location.href = `${this.baseURL}${this._format(
                link,
                this.item
            )}`;
        },
        isSmall(col) {
            return col.small === true;
        }
    }
};
</script>
