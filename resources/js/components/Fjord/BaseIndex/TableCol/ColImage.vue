<template>
    <div :class="`fj-col-image ${!src ? 'empty' : ''}`" :style="style">
        <fa-icon icon="image" v-if="!src" />
        <img :src="src" :style="style" v-else />
    </div>
</template>

<script>
export default {
    name: 'ColImage',
    props: {
        src: {
            type: String,
            required: true
        },
        maxWidth: {
            type: String,
            default() {
                return '70px';
            }
        },
        maxHeight: {
            type: String,
            default() {
                return '50px';
            }
        },
        square: {
            type: String
        }
    },
    computed: {
        style() {
            if (this.square) {
                return `width: ${this.square};height:${this.square};object-fit:cover;`;
            }
            return `max-width: ${this.maxWidth}; max-height: ${this.maxHeight};`;
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';

.fj-col-image {
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
    }
}
</style>
