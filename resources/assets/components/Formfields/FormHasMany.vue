<template>
    <fj-form-item :field="field" v-if="model.id">

        <b-card class="fjord-block no-fx mb-2">

            <fj-form-relation-table
                v-if="relations.length > 0"
                :items="relations"
                :field="field"
                :setItem="setItem"
                :noHeader="true"
                :sortable="true">

                <div slot-scope="{items}">
                    <b-table
                        outlined
                        table-variant="light"
                        :items="items"
                        :fields="fields"
                        thead-class="hidden-header"
                        class="mb-0 fj-relation-table">
                        <template slot="table-colgroup" slot-scope="scope">
                            <col
                              v-for="field in scope.fields"
                              :key="field.key"
                              :style="{ width: field.key === 'drag' ? '10px' : '180px' }"
                            >
                          </template>
                        <div slot="drag" slot-scope="{index}" class="text-right">
                            DRAG
                        </div>

                        <div slot="trash" slot-scope="{index}" class="text-right">
                            <a href="#" @click.prevent="removeRelation(index)" class="fj-trash text-muted">
                                <fa-icon icon="trash"/>
                            </a>
                        </div>
                    </b-table>
                </div>

            </fj-form-relation-table>

            <b-button
                class="mt-3"
                variant="secondary"
                size="sm"
                v-b-modal="modalId">
                {{ field.button }}
            </b-button>

        </b-card>

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
            if(this.fields.length == 0) {
                this.fields.push('drag')
                for(let key in item) {
                    this.fields.push(key)
                }
                this.fields.push('trash')
            }
            item.drag = model.id
            item.trash = model.id
            return item
        }
    },
    data() {
        return {
            relations: [],
            fields: [],
        }
    },
    beforeMount() {
        let items = this.model[this.field.id] || []

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
