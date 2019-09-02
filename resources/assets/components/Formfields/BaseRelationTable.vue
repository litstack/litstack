<template>
    <div>
        <slot :items="tableItems">
            <b-table
                v-bind:hover="select"
                striped
                :items="tableItems"
                :thead-class="{'hidden-header': true}"
                :tbody-class="select ? 'fj-select-table' : ''"
                class="mb-0"
                :busy="busy"
                @row-clicked="rowClicked">

                <div slot="table-busy" class="text-center my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Loading...</strong>
                </div>

                <template
                    :slot="'trash'"
                    slot-scope="data">
                    <slot :name="'trash'" :data="data"/>
                </template>

            </b-table>
        </slot>
    </div>
</template>

<script>
export default {
    name: 'FormRelationTable',
    props: {
        field: {
            required: true,
            type: Object
        },
        items: {
            type: Array,
            required: true
        },
        setItem: {
            type: Function,
        },
        select:{
            type: Boolean,
            default: false
        },
        busy: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        rowClicked(item, index, $event) {
            if(!this.select) {
                return
            }
            this.$emit('selected', this.items[index])
        },
        getTableItem(model) {
            let item = {}

            for(let i=0;i<this.field.preview.length;i++) {
                let key = this.field.preview[i]
                item[key] = model[key]
            }

            if(this.setItem) {
                item = this.setItem(item, model)
            }

            return item
        },
    },
    computed: {
        tableItems() {
            let items = []

            for(let i=0;i<this.items.length;i++) {
                items.push(this.getTableItem(this.items[i]))
            }

            return items
        },

    }
}
</script>

<style lang="css">
.hidden-header {
  display: none;
}
.fj-select-table tr:not(.b-table-busy-slot){
    cursor: pointer;
}
</style>
