<template>
	<lit-base-field :field="field" :model="model">
		<b-checkbox-group
			:checked="value"
			:options="field.options"
			:stacked="field.stacked"
			@input="$emit('input', $event)"
			class="lit-form-item-checkboxes"
		/>
	</lit-base-field>
</template>

<script>
export default {
	name: 'FieldCheckboxes',
	props: {
		field: {
			required: true,
			type: Object,
		},
		model: {
			required: true,
			type: Object,
		},
		value: {
			required: true,
		},
	},
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
.lit-form-item-checkboxes {
	height: $button-md-height / 1.5;
	align-items: center;
}
</style>
