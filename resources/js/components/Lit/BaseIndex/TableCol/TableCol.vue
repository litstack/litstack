<template>
    <b-td
        :class="{
            'col-sm': isSmall(col),
            'lit-table-col': true,
            'text-right': col.text_right,
            'text-center': col.text_center,
            ...col.classes,
        }"
        :style="`${colWidth} ${getStyle(col)}`"
    >
        <component
            :is="link ? 'a' : 'span'"
            :href="link"
            :target="isExternal(link) ? '_blank' : ''"
        >
            <lit-base-component
                v-if="col.name !== undefined"
                :component="component"
                :item="item"
                :format="getColValue"
                @reload="reload"
                v-on="$listeners"
            />

            <span v-else v-html="value" />
        </component>
    </b-td>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'TableCol',
    props: {
        item: {
            required: true,
            type: Object,
        },
        col: {
            required: true,
            type: Object,
        },
        cols: {
            required: true,
            type: Array,
        },
    },
    data() {
        return {
            value: '',
        };
    },
    watch: {
        item() {
            this.setValue();
        },
    },
    beforeMount() {
        this.setValue();

        // Can be called from parents to refresh value.
        this.$on('refresh', this.setValue);

        Lit.bus.$on('languageChanged', this.setValue);
    },
    computed: {
        ...mapGetters(['baseURL']),

        component() {
            return {
                ...this.col,
                props: {
                    ...this.getColComponentProps(),
                    ...this.$attrs,
                    value: this.value,
                },
            };
        },
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

            let path = this._format(this.col.link, this.item);

            if (!path.includes('//')) {
                return `${this.baseURL}${path}`;
            }

            return path;
        },
    },
    methods: {
        isExternal(url) {
            let domain = function(url) {
                return url
                    .replace('http://', '')
                    .replace('https://', '')
                    .split('/')[0];
            };

            if (!url) {
                return false;
            }

            if (!url.includes('//')) {
                return false;
            }

            return domain(location.href) !== domain(url);
        },
        setValue() {
            this.value = this.getColValue(this.col, this.item);
        },
        getValue() {
            this.setValue();
            return this.value;
        },
        reload() {
            this.$emit('reload');
        },
        getStyle(col) {
            let style = '';

            if (!col.style) {
                return style;
            }

            if (col.style_options) {
                style = this.getOption(
                    this.item,
                    col.style,
                    col.style_options,
                    col.style_value
                );
            } else {
                style = col.style;
            }

            return style;
        },
        getColValue(col, item) {
            let value = '';

            if(col.trans) {
                return this.translate(col, item);
            }

            if (col.value_options) {
                value = this.getOption(
                    item,
                    col.value,
                    col.value_options,
                    col.default_value
                );
            } else {
                value = col.value;
            }

            // Regex for has {value} pattern.
            if (/{(.*?)}/.test(value)) {
                value = this._format(value, item);
            } else if (item[value] !== undefined && item[value] !== null) {
                value = item[value];
            }

            return this.format(value);
        },
        translate(col, item) {
            if(col.trans_choice_attribute) {
                return this.trans_choice(
                    col.value, item[col.trans_choice_attribute], item
                );
            }
        
            return this.trans(col.value, item);
        },
        getOption(item, attribute, options, def) {
            let key = item[attribute];

            if (key === true) {
                key = 1;
            } else if (key === false) {
                key = 0;
            }

            let value = options[key];

            if (value === undefined && def) {
                value = def;
            }

            return value;
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
            if (!this.col.name) {
                return {};
            }

            let props = Lit.clone(this.col.props || {});

            if (!('eventData' in props)) {
                props.eventData = {};
            }

            props.eventData.ids = [this.item.id];
            props['event-data'] = { ids: [this.item.id] };

            return props;
        },
        isSmall(col) {
            return col.small === true;
        },
    },
};
</script>
<style lang="scss">
table.b-table tr td > a {
    display: inline-block;
    width: 100%;
    color: unset;
    &:hover {
        text-decoration: none;
    }
}

.lit-col-money {
    font-variant-numeric: tabular-nums;
}
</style>
