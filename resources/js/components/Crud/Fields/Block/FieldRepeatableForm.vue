<template>
	<b-row class="mt-3">
		<lit-field
			v-for="(field, key) in fields"
			:key="key"
			:field="field"
			:model-id="modelId"
			:model="block"
			v-on="$listeners"
		/>
	</b-row>
</template>

<script>
export default {
	name: 'FieldRepeatableForm',
	props: {
		block: {
			required: true,
			type: Object,
		},
		field: {
			required: true,
			type: Object,
		},
		model: {
			required: true,
			type: Object,
		},
		modelId: {
			required: true,
		},
		fields: {
			type: Array,
			required: true,
		},
	},
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
</style>
