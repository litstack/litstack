<template>
	<lit-col :width="width">
		<b-card :title="title">
			<b-row>
				<slot />
			</b-row>
		</b-card>
	</lit-col>
</template>

<script>
export default {
	name: 'FieldWrapperCard',
	props: {
		title: {
			type: String,
		},
		width: {
			type: Number,
			default() {
				return 12;
			},
		},
	},
};
</script>
