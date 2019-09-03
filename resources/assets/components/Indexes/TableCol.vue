<template>
    <div>
        <template v-if="col.type == 'image'">
            <img :src="item[col.key]" style="max-width: 70px;">
        </template>
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
    }
}
</script>

<style lang="css" scoped>
</style>
