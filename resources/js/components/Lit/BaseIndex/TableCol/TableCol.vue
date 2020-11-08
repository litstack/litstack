<template>
	<b-td
		:class="{
			'col-sm': isSmall(col),
			'lit-table-col': true,
			pointer: col.link,
			'text-right': col.text_right,
			'text-center': col.text_center,
			...col.classes,
		}"
		:style="colWidth"
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
		getColValue(col, item) {
			let value = '';

			if (col.value_options) {
				value = col.value_options[item[col.value]];

				if (value === undefined && col.default_value) {
					value = col.default_value;
				}
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

			let compiled = {
				'event-data': { ids: [this.item.id] },
			};

			for (let name in this.col.props) {
				let prop = this.col.props[name];
				compiled[name] = prop;
			}

			return compiled;
		},
		isSmall(col) {
			return col.small === true;
		},
	},
};
</script>
<style lang="scss">
table.b-table tr td > a {
	color: unset;
	&:hover {
		text-decoration: none;
	}
}
.lit-col-money {
	font-variant-numeric: tabular-nums;
}
</style>
