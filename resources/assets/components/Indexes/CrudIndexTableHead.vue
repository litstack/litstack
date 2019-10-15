<template>
    <thead>
        <b-tr>
            <b-th
                v-for="(col, key) in tableCols"
                :key="key"
            >
                <template v-if="col.key == 'check'">
                    <slot name="checkbox"/>
                </template>
                <template v-else>
                    {{ col.label }}
                </template>
            </b-th>
            <b-th v-if="hasRecordActions">
                
            </b-th>
        </b-tr>
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
