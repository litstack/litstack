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
        cols: {
            type: Number,
            default() {
                return 12;
            }
        }
    }
    /*
    computed: {
        responsiveCols() {
            return `xl-${this.cols} col-12`;
        }
    }
    */
};
</script>
