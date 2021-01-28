<template>
    <span v-html="icon"> </span>
</template>

<script>
export default {
    name: 'BaseIndexTableHeadSortIcon',
    props: {
        sort: {
            type: String,
        },
    },
    watch: {
        sort() {
            console.log({ sort: this.sort });
            this.$nextTick(() => {
                this.$forceUpdate();
            });
        },
    },
    computed: {},
};
</script>
