<template>
	<b-button
		variant="transparent"
		size="sm"
		class="btn-square"
		v-b-tooltip.hover
		:title="__('crud.fields.relation.edit')"
	>
		<lit-fa-icon icon="edit" @click="edit(item)" />
		<lit-field-relation-form
			:item="item"
			:id="rowModalId(item)"
			:field="field"
			@update="$emit('update')"
		/>
	</b-button>
</template>

<script>
export default {
	name: 'FieldRelationColEdit',
	props: {
		item: {
			type: Object,
			required: true,
		},
		field: {
			required: true,
			type: Object,
		},
	},
	methods: {
		rowModalId(item) {
			return `lit-row-${JSON.stringify(item).hash()}`;
		},
		edit(item) {
			this.$bvModal.show(this.rowModalId(item));
		},
	},
};
</script>
