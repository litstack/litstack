<template>
	<lit-container>
		<lit-navigation>
			<lit-user-create @userCreated="userCreated" slot="right" />
		</lit-navigation>
		<lit-header :title="'Lit ' + __('lit.users')" />

		<b-row>
			<b-col>
				<lit-index-table
					ref="indexTable"
					:cols="config.index"
					:items="users"
					:load-items="loadUsers"
					:name-singular="__('lit.user')"
					:name-plural="__('lit.users')"
					:per-page="config.perPage"
					:sort-by="config.sortBy"
					:sort-by-default="config.sortByDefault"
					:filter="config.filter"
					:global-actions="config.globalActions"
					:record-actions="config.recordActions"
				/>
			</b-col>
		</b-row>
	</lit-container>
</template>

<script>
export default {
	name: 'Users',
	props: {
		config: {
			required: true,
			type: Object,
		},
	},
	data() {
		return {
			users: [],
			data: {},
		};
	},
	methods: {
		reload() {
			this.$refs.indexTable.$emit('reload');
		},
		async loadUsers(payload) {
			let response = await axios.post('lit/users-index', payload);
			this.users = response.data.items;

			return response;
		},

		userCreated(user) {
			this.reload();
		},
	},
};
</script>
