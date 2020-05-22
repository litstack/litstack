<template>
    <b-col :cols="responsiveCols">
        <slot />
    </b-col>
</template>

<script>
export default {
    name: 'Col',
    props: {
        cols: {
            type: Number,
            default() {
                return 12;
            }
        }
    },
    computed: {
        responsiveCols() {
            return `xl-${this.cols} col-12`;
        }
    }
};
</script>
