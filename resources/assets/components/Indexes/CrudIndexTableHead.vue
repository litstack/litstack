<template>
    <thead>
        <tr>
            <th
                v-for="(col, key) in tableCols"
                :key="key"
            >
                <template v-if="col.key == 'check'">
                    <slot name="checkbox"/>
                </template>
                <template v-else>
                    {{ col.label }}
                </template>
            </th>
            <th v-if="hasRecordActions">

            </th>
        </tr>
    </thead>
</template>

<script>
export default {
    name: 'CrudIndexTableHead',
    props: {
        tableCols: {
            type: Array,
            required: true
        },
        hasRecordActions: {
            type: Boolean,
            required: true
        },
        selectedItems: {
            type: Array,
            required: true
        }
    }
}
</script>
