<template>
    <b-col cols="12" :xl="cols">
        <slot />
    </b-col>
</template>

<script>
/**
 * Responsive bootstrap col wrapper.
 */
export default {
    name: 'Col',
    props: {
        width: {
            type: Number,
            default() {
                return 12;
            }
        }
    },
    computed: {
        cols() {
            if (this.width >= 1) {
                return this.width;
            }

            return Math.round(12 * this.width);
        }
    }
};
</script>
