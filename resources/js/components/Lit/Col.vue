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
            type: [Number, String],
            default() {
                return 12;
            },
        },
    },
    computed: {
        cols() {
            return this.bCols(Number(this.width));
        },
    },
};
</script>
