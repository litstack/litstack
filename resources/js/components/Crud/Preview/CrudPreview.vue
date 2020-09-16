<template>
	<span>
		<lit-fa-icon icon="eye" />

		{{ __('lit.preview') }}

		<lit-page-preview :show="true" :route="url" />
	</span>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
	name: 'CrudPreview',
	props: {
		url: {
			type: String,
			required: true,
		},
	},
	mounted() {
		this.$nextTick(() => {
			this.$parent.$parent.$el.addEventListener('click', () => {
				this.$bvModal.show('lit-page-preview');
			});
		});
	},
};
</script>
