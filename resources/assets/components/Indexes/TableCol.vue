<template>
    <div>
        <template v-if="col.type == 'image'">
            <img :src="item[col.key]" style="max-width: 70px;">
        </template>
        <div v-else-if="'values' in col" v-html="getColValue(col, item[col.key])"/>
        <template v-else>
            {{ item[col.key] }}
        </template>
    </div>
</template>

<script>
export default {
    name: 'TableCol',
    props: {
        item: {
            required: true,
            type: Object
        },
        col: {
            required: true,
            type: Object
        }
    },
    methods: {
        getColValue(col, value) {
            let checkValue = value
            checkValue = checkValue === true ? '1' : checkValue
            checkValue = checkValue === false ? '0' : checkValue

            if(checkValue in col.values) {
                return col.values[checkValue]
            }

            if('default' in col.values) {
                return col.values.default
            }

            return value
        }
    }
}
</script>

<style lang="css" scoped>
</style>
