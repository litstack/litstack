<template>
	<div>
		<b-button variant="secondary" size="sm" @click="toggle">
			{{ __('base.toggle_all') }}
		</b-button>
	</div>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
	name: 'PermissionsToggleAll',
	props: {
		item: {
			required: true,
			type: [Object, Array],
		},
		col: {
			required: true,
			type: Object,
		},
	},
	methods: {
		toggle() {
			let count = 0;
			for (let i = 0; i < this.litPermissionsOperations.length; i++) {
				let operation = this.litPermissionsOperations[i];
				if (this.roleHasPermission(operation)) {
					count++;
				}
			}
			// toggle on if half or more of the operations are off
			let on = count <= this.litPermissionsOperations.length / 2;

			this.$bus.$emit('litPermissionsToggleAll', {
				on,
				group: this.group,
			});

			this.$bvToast.toast(
				this.__('permissions.messages.all_permission_updated', {
					group: this.$te(`permissions.${this.group}`)
						? this.__(`permissions.${this.group}`).capitalize()
						: this.group.capitalize(),
				}),
				{
					variant: 'success',
				}
			);
		},
		roleHasPermission(operation) {
			let permission = this.getPermission(operation);

			if (!permission) {
				return false;
			}
			return (
				_.size(
					_.filter(this.litPermissionsRolePermissions, {
						role_id: this.litPermissionsRole.id,
						permission_id: permission.id,
					})
				) > 0
			);
		},
		getPermission(operation) {
			for (let id in this.litPermissionsPermissions) {
				let permission = this.litPermissionsPermissions[id];
				if (permission.name == `${operation} ${this.group}`) {
					return permission;
				}
			}
		},
	},
	computed: {
		...mapGetters([
			'litPermissionsOperations',
			'litPermissionsRole',
			'litPermissionsPermissions',
			'litPermissionsRolePermissions',
		]),
		group() {
			return this.item.name
				.split(' ')
				.slice(1)
				.join(' ');
		},
	},
};
</script>
