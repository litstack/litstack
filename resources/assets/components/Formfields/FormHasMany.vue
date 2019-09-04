<template>
    <fj-form-item :field="form_field" v-if="model.id">

        <b-card class="fjord-block no-fx mb-2">

            <div>

                    <b-table-simple
                        outlined
                        table-variant="light">

                        <fj-colgroup
                            :icons="['drag', 'trash']"
                            :cols="fields"/>

                        <draggable
                            v-model="relations"
                            @end="newOrder(relations)"
                            tag="tbody"
                            handle=".fj-draggable__dragbar">
                            
                            <b-tr v-for="(relation, rkey) in relations" :key="rkey">
                                <b-td
                                    style="vertical-align: middle;"
                                    v-for="(field, key) in fields"
                                    :key="`td-${key}`"
                                    :class="field.key == 'drag' ? 'fj-draggable__dragbar' : ''">
                                    <div v-if="field.key == 'drag'" class="text-center text-muted">
                                        <fa-icon icon="grip-vertical"/>
                                    </div>
                                    <div v-else-if="field.key == 'trash'" class="text-center">
                                        <a href="#" @click.prevent="removeRelation(relation.id)" class="fj-trash text-muted">
                                            <fa-icon icon="trash"/>
                                        </a>
                                    </div>
                                    <div v-else>
                                        <fj-table-col :item="relation" :col="field"/>
                                    </diV>
                                </b-td>
                            </b-tr>

                        </draggable>

                    </b-table-simple>

            </div>

            <b-button
                variant="secondary"
                size="sm"
                v-b-modal="modalId">
                {{ form_field.button }}
            </b-button>

        </b-card>

        <slot />

        <fj-form-relation-modal
            :field="form_field"
            :model="model"
            :selectedModels="relations"
            @selected="selected"
            @remove="removeRelation"/>

    </fj-form-item>
</template>

<script>
import TranslatableEloquent from './../../eloquent/translatable'
import TableModel from './../../eloquent/table.model'

export default {
    name: 'FormHasMany',
    props: {
        form_field: {
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
            this.relations.push(new TableModel(item))
        },
        async selected(item) {

            let payload = {
                from_model_type: this.model.model,
                from_model_id: this.model.id,
                to_model_type: this.form_field.model,
                to_model_id: item.id
            }

            try {
                await axios.post('relations/store', payload)
            } catch(e) {
                this.$notify({
                    group: 'general',
                    type: 'warning',
                    title: this.form_field.title,
                    text: e.response.data.message,
                });
                return
            }

            this.relations.push(item)

            this.$notify({
                group: 'general',
                type: 'success',
                title: this.form_field.title,
                text: `Added item to ${this.form_field.title}`,
            });

            //this.$bvModal.hide(this.modalId)
        },
        async removeRelation(id, $event) {
            let relation = this.relations.find(r => r.id == id)
            let index = this.relations.indexOf(relation)

            this.relations.splice(index, 1)

            axios.delete(`relations/${index}`)

            this.$notify({
                group: 'general',
                type: 'success',
                title: this.form_field.title,
                text: `Removed item from ${this.form_field.title}`,
            });
        },
        async newOrder() {
            let ids = []

            let relation_type = {
                from_model_type: this.model.model,
                from_model_id: this.model.id,
                to_model_type: this.form_field.model,
            }
            for(let i=0;i<this.relations.length;i++) {
                let relation = this.relations[i]
                ids.push(relation.id)
            }

            let payload = {
                data: relation_type,
                ids
            };

            let response = await axios.put('relations/order', payload)

            this.$notify({
                group: 'general',
                type: 'success',
                title: this.form_field.title,
                text: 'Changed order.',
            });
        },
        setFields() {
            this.fields.push({key: 'drag'})
            for(let i=0;i<this.form_field.preview.length;i++) {
                let field = this.form_field.preview[i]

                if(typeof field == typeof '') {
                    field = {key: field}
                }
                this.fields.push(field)
            }
            this.fields.push({key: 'trash'})
        },
        colSize(field)
        {
            if(field.key == 'trash' || field.key == 'drag') {
                return '10px'
            }

            if(field.type == 'image') {
                return '40px'
            }

            return '100%'
        }
    },
    data() {
        return {
            relations: [],
            fields: [],
        }
    },
    beforeMount() {
        this.setFields()

        let items = this.model[this.form_field.id] || []

        for(let i=0;i<items.length;i++) {
            this.addRelation(items[i])
        }
    },
    computed: {
        modalId() {
            return `${this.model.route}-form-relation-table-${this.form_field.id}`
        },
    }
};
</script>

<style lang="scss" scoped>
.fjord-draggable{
    margin-bottom: 5px;
}
</style>
