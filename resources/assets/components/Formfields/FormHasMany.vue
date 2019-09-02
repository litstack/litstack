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

                        <div slot="trash" slot-scope="{index}" class="text-right">
                            <a href="#" @click.prevent="removeRelation(index)" class="fj-trash text-muted">
                                <fa-icon icon="trash"/>
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
        selected(item) {
            this.addRelation(item.data)

            let payload = {
                from_model_type: this.model.model,
                from_model_id: this.model.id,
                to_model_type: this.field.model,
                to_model_id: item.id
            }

            axios.post('relations/store', payload)

            this.$notify({
                group: 'general',
                type: 'aw-success',
                title: this.field.title,
                text: `Added item to ${this.field.title}`,
                duration: 1500
            });

            //this.$bvModal.hide(this.modalId)
        },
        async removeRelation(index, $event) {
            this.relations.splice(index, 1)

            console.log(index)

            axios.delete(`relations/${index}`)

            this.$notify({
                group: 'general',
                type: 'aw-success',
                title: this.field.title,
                text: `Removed item from ${this.field.title}`,
                duration: 1500
            });
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
        let items = this.model[this.field.id] || []
        console.log(items)

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
