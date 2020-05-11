<template>
    <b-td
        :class="{
            small: isSmall(col),
            'fj-table-col': true,
            pointer: col.link
        }"
        :style="colWidth"
    >
        <a :href="link" :is="link ? 'a' : 'div'">
            <component
                v-if="col.component !== undefined"
                :is="col.component"
                :item="item"
                :col="col"
                :format="getColValue"
                @reload="reload"
                v-on="$listeners"
                v-bind="getColComponentProps()"
            />
            <span v-else v-html="value" />
        </a>
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
    watch: {
        item() {
            this.setValue();
        }
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
        },
        link() {
            if (!this.col.link) {
                return;
            }

            return `${this.baseURL}${this._format(this.col.link, this.item)}`;
        }
    },
    methods: {
        setValue() {
            console.log('set value');
            this.value = this.getColValue(this.col.value, this.item);
        },
        reload() {
            this.$emit('reload');
        },
        getColValue(col, item) {
            let value = '';

            // Regex for has {value} pattern.
            if (/{(.*?)}/.test(col)) {
                value = this._format(col, item);
            } else if (item[col] !== undefined && item[col] !== null) {
                value = item[col];
            } else {
                value = col;
            }

            return this.format(value);
        },
        format(value) {
            if (!value) {
                return value;
            }

            if (this.col.regex) {
                value = value.replace(
                    eval(this.col.regex),
                    this.col.regex_replace
                );
            }

            if (this.col.strip_html) {
                value = value.replace(/<[^>]*>?/gm, ' ');
            }

            if (this.col.max_chars) {
                if (value.length > this.col.max_chars) {
                    value = value.substring(0, this.col.max_chars) + '...';
                }
            }

            return value;
        },
        getColComponentProps() {
            if (!this.col.component) {
                return {};
            }

            let compiled = {};

            for (let name in this.col.props) {
                let prop = this.col.props[name];
                compiled[name] = prop;
            }

            return compiled;
        },
        isSmall(col) {
            return col.small === true;
        }
    }
};
</script>
