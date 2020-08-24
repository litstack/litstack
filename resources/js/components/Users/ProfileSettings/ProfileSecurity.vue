<template>
	<lit-col :width="12">
		<lit-index-table
			:items="sessions"
			:cols="tableCols"
			:loadItems="loadSessions"
			name-singular="device"
			name-plural="devices"
			no-select
			no-card
		>
			<template slot="header">
				<h5>{{ __('profile.logged_in_devices').capitalizeAll() }}</h5>
			</template>
		</lit-index-table>
	</lit-col>
</template>

<script>
export default {
	name: 'ProfileSecurity',
	props: {
		model: {
			type: Object,
			required: true,
		},
	},
	data() {
		return {
			sessions: [],
			tableCols: [
				{
					name: 'lit-profile-security-session-device',
					label: this.__('base.device').capitalize(),
					small: true,
				},
				{
					name: 'lit-profile-security-session-current',
					label: '',
				},
				{
					name: 'lit-profile-security-session-location',
					label: this.__('base.location').capitalize(),
				},
				{
					value: '{last_activity_readable}',
					label: this.__('profile.last_activity').capitalizeAll(),
				},
				{
					name: 'lit-profile-security-session-logout',
					label: '',
					small: true,
				},
			],
		};
	},
	methods: {
		async loadSessions(payload) {
			let response = await axios.get('profile-sessions', payload);
			this.sessions = response.data.items;

			return response;
		},
	},
};
</script>
<style lang="scss">
@import '@lit-sass/_variables';
.profile-security .lit-index-table {
	margin-left: -$card-spacer-x;
	margin-right: -$card-spacer-x;
}
</style>
