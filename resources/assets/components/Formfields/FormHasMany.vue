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

                        <b-table-simple
                            outlined
                            table-variant="light">

                            <colgroup>
                                <col
                                    v-for="key in fields"
                                    :key="key"
                                    :style="{ width: key == ('drag' || 'trash') ? '10px' : '100%' }"
                                    />
                            </colgroup>

                            <draggable
                                v-model="items"
                                @end="newOrder(items)"
                                tag="tbody"
                                handle=".fj-draggable__dragbar">

                                <b-tr v-for="(item, key) in items" :key="key">
                                    <b-td
                                        v-for="key in fields"
                                        :key="key"
                                        :class="key == 'drag' ? 'fj-draggable__dragbar' : ''"
                                        >
                                        <div v-if="key == 'drag'" class="text-center text-muted">
                                            <fa-icon icon="grip-vertical"/>
                                        </div>
                                        <div v-else-if="key == 'trash'" class="text-center">
                                            <a href="#" @click.prevent="removeRelation(item[key])" class="fj-trash text-muted">
                                                <fa-icon icon="trash"/>
                                            </a>
                                        </div>
                                        <div v-else>
                                            {{ item[key] }}
                                        </diV>
                                    </b-td>
                                </b-tr>

                            </draggable>

                        </b-table-simple>

                </div>

            </fj-form-relation-table>

            <b-button
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
        async removeRelation(id, $event) {
            let relation = this.relations.find(r => r.id == id)
            let index = this.relations.indexOf(relation)

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
        },
        async newOrder(items) {
            let relations = []
            let ids = []

            let relation_type = {
                from_model_type: this.model.model,
                from_model_id: this.model.id,
                to_model_type: this.field.model,
            }
            for(let i=0;i<items.length;i++) {
                let item = items[i]
                let relation = this.relations.find(r => r.id == item.drag)
                relations.push(relation)
                ids.push(relation.id)
            }
            this.relations = relations

            let payload = {
                data: relation_type,
                ids
            };

            await axios.put('relations/order', payload)

            this.$notify({
                group: 'general',
                type: 'aw-success',
                title: this.field.title,
                text: 'Changed order.',
                duration: 1500
            });
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
