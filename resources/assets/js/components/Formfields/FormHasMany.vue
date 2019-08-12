<template>
    <fj-form-item :field="field" v-if="model.id">

        <b-card class="fjord-block no-fx mb-2">

            <fj-form-relation-table
                v-if="relations.length > 0"
                :items="relations"
                :field="field"
                :setItem="setItem"
                :noHeader="true">

                <div slot-scope="{items}">
                    <b-table
                        outlined
                        striped
                        :items="items"
                        thead-class="hidden-header"
                        class="mb-0">

                        <div slot="trash" slot-scope="{data}" class="text-right">
                            <a href="#" @click.prevent="removeRelation(data)">
                                <i class="far fa-times-circle"></i>
                            </a>
                        </div>

                    </b-table>
                </div>

            </fj-form-relation-table>

        </b-card>

        <b-button
            variant="primary"
            v-b-modal="modalId">
            {{ field.button }}
        </b-button>

        <slot />

        <fj-form-relation-modal :field="field" :model="model" @selected="selected"/>

    </fj-form-item>
</template>

<script>
import TranslatableEloquent from './../../eloquent/translatable'

export default {
    name: 'FormHasMany',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        }
    },
    methods: {
        async addRelation(item) {
            this.relations.push(new TranslatableEloquent(item))
        },
        _removeRelation(index) {
            this.relations.splice(index, 1)

            axios.delete(`/admin/relations/${index}`)
        },
        selected(item) {
            this.addRelation(item.data)

            let payload = {
                from_model_type: this.model.model,
                from_model_id: this.model.id,
                to_model_type: this.field.config.model,
                to_model_id: item.id
            }

            axios.post('/admin/relations/store', payload)

            //this.$bvModal.hide(this.modalId)
        },
        removeRelation(row, $event) {
            console.log(row)
            let items = this.relations
            for(let i=0;i<items.length;i++) {
                let item = items[i]
                if(item.id == row.value) {
                    this._removeRelation(i)
                }
            }
        },
        setItem(item, model) {
            item.trash = model.id

            return item
        }
    },
    data() {
        return {
            relations: []
        }
    },
    beforeMount() {
        let items = this.field.item[this.field.id] || []

        for(let i=0;i<items.length;i++) {
            this.addRelation(items[i])
        }
    },
    computed: {
        modalId() {
            return `${this.model.route}-form-relation-table-${this.field.id}`
        },
    }
};
</script>

<style lang="scss" scoped>
.fjord-draggable{
    margin-bottom: 5px;
}
</style>
