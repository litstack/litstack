<template>
	<lit-container fluid="fluid">
		<lit-navigation />
		<lit-header
			:title="
				__('base.item_settings', {
					item: __('base.profile'),
				}).capitalizeAll()
			"
		/>

		<b-row>
			<components
				v-for="(component, key) in config.form.components"
				:key="key"
				:is="component.name"
				v-bind="component.props"
				:model="profile"
				:config="config"
			/>
		</b-row>
	</lit-container>
</template>

<script>
export default {
	name: 'ProfileSettings',
	props: {
		model: {
			required: true,
			type: Object,
		},
		config: {
			required: true,
			type: Object,
		},
	},
	data() {
		return {
			profile: {},
		};
	},
	beforeMount() {
		this.profile = this.crud(this.model);
	},
};
</script>
