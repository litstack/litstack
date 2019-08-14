<template>
    <fj-form-item :field="field">

        <b-card class="fjord-block no-fx mb-2">

            <fj-form-relation-table
                v-if="relation"
                :items="[relation]"
                :field="field"
                :setItem="setItem"
                :noHeader="true">

                <div
                    class="fjord-draggable mb-0"
                    slot-scope="{items}">

                    <b-table
                        borderless
                        :items="items"
                        :thead-class="'hidden-header'"
                        class="mb-0">

                        <div slot="trash" slot-scope="row" class="text-right">
                            <a href="#" @click.prevent="removeRelation"><i class="far fa-times-circle"></i></a>
                        </div>

                    </b-table>
                </div>

            </fj-form-relation-table>

            <div v-else class="text-center">

                <span class="text-muted">
                    No {{ field.title }} selected.
                </span>
            </div>

        </b-card>


        <b-button
            variant="secondary"
            size="sm"
            v-b-modal="modalId">

            <fa-icon icon="plus"/> {{ field.button }}

        </b-button>

        <slot />

        <fj-form-relation-modal :field="field" :model="model" @selected="selected"/>

    </fj-form-item>
</template>

<script>
import TranslatableEloquent from './../../eloquent/translatable'
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
    methods: {
        selected(item) {
            // TODO: remove save job if old one
            this.field.item[this.field.id] = item.data

            let job = {
                route: 'relation',
                method: 'put',
                data: {
                    model: this.model.model,
                    id: this.model.id,
                    key: this.field.local_key,
                    value: item.data.id
                }
            }
            this.$store.commit('addSaveJob', job)

            this.$bvModal.hide(this.modalId)
        },
        removeRelation() {
            this.field.item[this.field.id] = null
            //this.$emit('changed')
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
        relation() {
            let relation = this.field.item[this.field.id]
            if(relation) {
                return new TranslatableEloquent(relation)
            }
        },
    }
};
</script>
