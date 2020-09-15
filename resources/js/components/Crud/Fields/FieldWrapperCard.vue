<template>
	<lit-col :width="width">
		<b-card class="lit-crud-card">
			<h6
				v-if="title"
				v-html="title"
				class="mb-4"
				style="font-size:1.125rem"
			></h6>
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
<style lang="scss">
.crud-card-secondary .lit-crud-card {
	background: #f5f8fb;
	border: 1px solid #e0e9f1;
}
</style>
