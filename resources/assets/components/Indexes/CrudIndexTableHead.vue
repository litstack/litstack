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
                    <div @click="sortCol(col.key)">
                        {{ col.label }}
                        <fa-icon class="ml-2" icon="sort-alpha-down" v-if="sort == 'desc'"/>
                        <fa-icon class="ml-2" icon="sort-alpha-down-alt" v-if="sort == 'asc'"/>
                    </div>
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
    },
    data(){
        return {
            sort: null
        }
    },
    methods: {
        sortCol(key){
            if(this.sort == null){
                this.sort = 'asc'
            }

            let sort = key.replace('{', '').replace('}', '').concat(`.${this.sort}`)
            this.$emit('sort', sort);

            switch (this.sort) {
                case 'asc':
                    this.sort = 'desc'
                    break;
                case 'desc':
                    this.sort = 'asc'
                    break;
            }
        }
    }
}
</script>
