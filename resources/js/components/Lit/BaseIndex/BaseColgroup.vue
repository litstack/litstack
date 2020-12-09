<template>
    <colgroup>
        <col v-for="col in cols" :style="{ width: colSize(col) }" />
    </colgroup>
</template>

<script>
export default {
    name: 'BaseColgroup',
    props: {
        icons: {
            type: Array,
            default: [],
        },
        cols: {
            required: true,
            type: Array,
        },
    },
    data() {
        return {
            componentWidths: {
                'lit-col-image': '50px',
            },
        };
    },
    computed: {
        percentageColsCount() {
            let count = 0;
            for (let i = 0; i < this.cols.length; i++) {
                let col = this.cols[i];

                if ('width' in col) {
                    continue;
                }

                if (col.component in this.componentWidths) {
                    continue;
                }

                if (this.icons.includes(col.value)) {
                    continue;
                }

                count++;
            }
            return count;
        },
    },
    methods: {
        colSize(col) {
            if (this.icons.includes(col.value)) {
                return '10px';
            }

            if ('width' in col) {
                return col.width;
            }

            if (col.component in this.componentWidths) {
                return this.componentWidths[col.component];
            }

            return 100 / this.percentageColsCount + '%';
        },
    },
};
</script>

<style lang="css" scoped></style>
