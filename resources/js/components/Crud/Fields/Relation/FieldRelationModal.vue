<template>
	<b-modal
		:id="modalId"
		size="lg"
		hide-footer
		:title="title"
		content-class="lit-relation-modal"
	>
		<lit-index-table
			ref="table"
			:cols="cols"
			:items="items"
			:load-items="loadItems"
			:search-keys="field.search"
			:per-page="10"
			:name-singular="field.names.singular"
			:name-plural="field.names.plural"
			:selected="selectedRelations"
			@select="select"
			@unselect="remove"
			v-bind:radio="!field.many"
			no-card
			no-head
			small
		/>
	</b-modal>
</template>

<script>
export default {
	name: 'FieldRelationModal',
	props: {
		field: {
			required: true,
			type: Object,
		},
		model: {
			required: true,
			type: Object,
		},
		modalId: {
			type: String,
			required: true,
		},
		cols: {},
		selectedRelations: {
			type: [Object, Array],
			default: () => {
				return {};
			},
		},
	},
	beforeMount() {
		this.$on('refresh', () => {
			this.$refs.table.$emit('refreshSelected');
		});
	},
	data() {
		return {
			items: [],
		};
	},
	methods: {
		remove(item) {
			this.$emit('remove', item);
		},
		select(item) {
			this.$emit('select', item);
		},
		async loadItems(payload) {
			let response = await this.sendLoadItems(payload);

			if (!response) {
				return;
			}

			this.items = this.crud(response.data.items);

			return response;
		},

		/**
		 * Send load items request.
		 */
		async sendLoadItems(payload) {
			try {
				return await axios.post(
					`${this.field.route_prefix}/relation/index`,
					{ field_id: this.field.id, ...payload }
				);
			} catch (e) {
				console.log(e);
			}
		},
	},
	computed: {
		title() {
			return this.field.many
				? this.__('lit.add_model', { model: this.field.title })
				: this.__('lit.select_item', { item: this.field.title });
		},
	},
};
</script>

<style lang="scss">
.lit-relation-modal {
	.modal-body {
		padding: 0;
	}
}
</style>
