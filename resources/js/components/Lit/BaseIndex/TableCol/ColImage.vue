<template>
	<div :class="`lit-col-image ${!srcValue ? 'empty' : ''}`" :style="style">
		<lit-fa-icon icon="image" v-if="!srcValue" />
		<img :src="srcValue" :style="style" v-else />
	</div>
</template>

<script>
export default {
	name: 'ColImage',
	props: {
		src: {
			type: String,
			required: true,
		},
		maxWidth: {
			type: String,
			default() {
				return '70px';
			},
		},
		maxHeight: {
			type: String,
			default() {
				return '50px';
			},
		},
		square: {
			type: Boolean,
		},
		item: {
			type: Object,
		},
		format: {
			type: Function,
		},
	},
	computed: {
		srcValue() {
			return this.format({ value: this.src }, this.item);
		},
		style() {
			if (this.square) {
				return `width: ${this.square};height:${this.square};object-fit:cover;`;
			}
			return `max-width: ${this.maxWidth}; max-height: ${this.maxHeight};`;
		},
	},
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';

.lit-col-image {
	border-radius: 3px;

	img {
		border-radius: 3px;
	}

	&.empty {
		display: flex;
		justify-content: center;
		align-items: center;
		font-size: $font-size-lg;
		color: $gray-500;
		background: $gray-300;
		width: 2.5rem;
		height: 2.5rem;
	}
}
</style>
