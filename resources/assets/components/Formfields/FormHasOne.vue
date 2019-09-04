<template>
    <fj-form-item :field="field">

        <b-card class="fjord-block no-fx mb-2">
            <b-table-simple
                v-if="model[`${field.id}Model`]"
                outlined
                table-variant="light">

                <fj-colgroup
                    :icons="['drag', 'trash']"
                    :cols="cols"/>

                <b-tr>
                    <b-td
                        style="vertical-align: middle;"
                        v-for="(col, key) in cols"
                        :key="`td-${key}`"
                        :class="col.key == 'drag' ? 'fj-draggable__dragbar' : ''">
                        <div v-if="col.key == 'trash'" class="text-center">
                            <a href="#" @click.prevent="removeRelation(relation.id)" class="fj-trash text-muted">
                                <fa-icon icon="trash"/>
                            </a>
                        </div>
                        <div v-else>
                            <fj-table-col :item="relation" :col="col"/>
                        </diV>
                    </b-td>
                </b-tr>
            </b-table-simple>
            <div v-else class="text-center">
                <span class="text-muted">
                    No {{ field.title }} selected.
                </span>
            </div>

            <b-button
                variant="secondary"
                size="sm"
                v-b-modal="modalId">
                {{ field.button }}
            </b-button>

        </b-card>

        <slot />

        <fj-form-relation-modal
            :field="field"
            :model="model"
            :hasMany="false"
            :selectedModels="[relation]"
            @selected="selected"/>

    </fj-form-item>
</template>

<script>
import TranslatableEloquent from './../../eloquent/translatable'
import TableModel from './../../eloquent/table.model'
import { mapGetters } from 'vuex'

export default {
    name: 'FormHasOne',
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
    data() {
        return {
            relation: {},
            cols: [],
        }
    },
    beforeMount() {
        this.setCols()
        let relation = this.model[this.field.id]

        if(relation) {
            this.relation = new TableModel(relation)
        }
    },
    methods: {
        setCols() {
            for(let i=0;i<this.field.preview.length;i++) {
                let col = this.field.preview[i]

                if(typeof col == typeof '') {
                    col = {key: col}
                }
                this.cols.push(col)
            }
            this.cols.push({key: 'trash'})
        },
        selected(item) {
            // TODO: remove save job if old one
            this.model[`${this.field.id}Model`] = item.id
            this.relation = item

            this.$emit('changed')
            this.$bvModal.hide(this.modalId)
        },
        removeRelation() {
            this.model[`${this.field.id}Model`] = null
            this.relation = null

            this.$emit('changed')
        },
        setItem(item) {
            item.trash = ''
            return item
        }
    },
    computed: {
        ...mapGetters([
            'lng'
        ]),
        modalId() {
            return `${this.model.route}-form-relation-table-${this.field.id}`
        },

    }
};
</script>
