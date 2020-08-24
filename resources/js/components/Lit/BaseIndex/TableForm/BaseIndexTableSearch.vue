<template>
	<b-form-input
		:placeholder="
			__('lit.search_models', {
				models: namePlural,
			})
		"
		v-model="query"
	/>
</template>

<script>
export default {
	name: 'BaseIndexTableSearch',
	props: {
		nameSingular: {
			type: String,
		},
		namePlural: {
			type: String,
		},
	},
	data() {
		return {
			query: '',
			typingDelay: 500,
		};
	},
	watch: {
		query(val) {
			setTimeout(() => {
				if (this.query == val) {
					this.$emit('search', val);
				}
			}, this.typingDelay);
		},
	},
};
</script>
