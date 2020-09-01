<template>
	<b-button
		variant="transparent"
		size="sm"
		class="btn-square"
		v-b-tooltip.hover
		:title="__('crud.fields.relation.unlink')"
		@click="$emit('unlink', item)"
	>
		<lit-fa-icon icon="unlink" />
	</b-button>
</template>

<script>
export default {
	name: 'FieldRelationColUnlink',
	props: {
		item: {
			type: Object,
			required: true,
		},
	},
};
</script>
