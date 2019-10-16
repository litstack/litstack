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
                    <div
                        @click="sortCol(col.key, key)"
                        class="pointer"
                        :class="{'text-muted': (key != activeCol && activeCol != null)}">
                        {{ col.label }}
                        <span class="d-inline-block ml-2" v-if="key == activeCol">
                            <fa-icon icon="sort-alpha-down" v-if="sort == 'desc'"/>
                            <fa-icon icon="sort-alpha-down-alt" v-if="sort == 'asc'"/>
                        </span>
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
            sort: null,
            activeCol: null
        }
    },
    methods: {
        sortCol(key, index){
            if(this.sort == null){
                this.sort = 'asc'
            }
            this.activeCol = index

            // TODO: this is a bit hacky
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

<style lang="css" scoped>
.pointer{
    cursor: pointer;
}
</style>
