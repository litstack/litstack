<template>
    <colgroup>
        <col
            v-for="col in cols"
            :style="{ width: colSize(col) }"
            />
    </colgroup>
</template>

<script>
export default {
    name: 'BaseColgroup',
    props: {
        icons: {
            type: Array,
            default: []
        },
        cols: {
            required: true,
            type: Array
        }
    },
    data() {
        return {
            widths: {
                image: '70px'
            }
        }
    },
    computed: {
        percentageColsCount() {
            let count = 0
            for(let i=0;i<this.cols.length;i++) {
                let col = this.cols[i]

                if(col.type in this.widths) {
                    continue
                }

                if(this.icons.includes(col.key)) {
                    continue
                }

                count++
            }
            return count
        }
    },
    methods: {
        colSize(col) {
            if(this.icons.includes(col.key)) {
                return '10px'
            }

            if(col.type in this.widths) {
                return this.widths[col.key]
            }

            return (100 / this.percentageColsCount) + '%'

        }
    }
}
</script>

<style lang="css" scoped>
</style>
