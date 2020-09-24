<template>
	<b-alert
		show
		variant="info"
		v-html="__('messages.no_relation_selected', { relation: field.title })"
	/>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
	name: 'FieldAlertEmpty',
	props: {
		field: {
			type: Object,
			required: true,
		},
	},
	computed: {
		...mapGetters(['config']),
	},
};
</script>
