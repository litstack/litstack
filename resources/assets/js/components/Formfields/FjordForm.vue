<template>

    <form class="row">
        <template v-for="m in preparedModels">
            <div
                :class="fieldWidth(field)"
                v-for="(field, index) in m.formFields">

                <fj-form-input
                    v-if="field.type == ('input')"
                    :field="field"
                    @changed="changed(field, m)"/>

                <fj-form-boolean
                    v-if="field.type == ('boolean')"
                    v-model="field.model"
                    :field="field"
                    @input="changed(field, m)"/>

                <fj-form-select
                    v-if="field.type == ('select')"
                    :field="field"
                    @changed="changed(field, m)"/>

                <fj-form-textarea
                    v-if="field.type == ('textarea')"
                    :field="field"
                    @changed="changed(field, m)"/>

                <fj-form-wysiwyg
                    v-if="field.type == ('wysiwyg')"
                    :field="field"
                    @changed="changed(field, m)"/>

                <fj-form-media
                    v-if="field.type == ('image')"
                    :field="field"
                    :id="m.id"
                    :model="m"
                    :media="m[field.id]"
                    />

                <fj-form-block
                    v-if="field.type == ('block') && m.id"
                    :field="field"
                    :repeatables="m.relations.repeatables"
                    :model="m"
                    v-model="field.model"
                    @newRepeatable="(repeatable) => {newRepeatable(m, repeatable)}"
                    />

                <template v-if="field.type == ('relation')">

                    <fj-form-has-many
                        v-if="field.many"
                        :field="field"
                        :model="m"
                        />
                    <fj-form-has-one
                        v-else
                        :field="field"
                        :model="m"
                        @changed="changed(field, m)"
                        />

                </template>

            </div>
        </template>
        <div class="col-12">
            <div class="d-flex justify-content-end">
                <!-- TODO: DELETE OR SOMETHING-->
            </div>
        </div>
    </form>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import Eloquent from './../../eloquent'
import EloquentCollection from './../../eloquent/collection'
import {mapGetters} from 'vuex'

export default {
    name: 'Form',
    props: {
        model: {
            type: Object,
            required: true,
        },
        repeatables: {
            type: Object
        }
    },
    computed: {
        ...mapGetters(['lng'])
    },
    beforeMount() {
        if(this.model instanceof Eloquent) {
            this.preparedModels = [this.model]
        } else {
            this.preparedModels = this.model.items.items
        }
    },
    data() {
        return {
            preparedModels: [],
            editor: ClassicEditor,
            editorConfig: {
                removePlugins: [],
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        'link',
                        'undo',
                        'redo',
                        'bulletedList',
                        'numberedList',
                        'blockQuote'
                    ]
                }
            }
        }
    },
    methods: {
        changed(field, model) {
            console.log(field.originalModel == field.model, field.originalModel, field.model)
            if(field.originalModel == field.model) {
                this.$store.commit('removeModelFromSave', {model, id: field.id})
            } else {
                this.$store.commit('addModelToSave', {model, id: field.id})
            }
        },
        toggle(field) {
            field.model = !field.model
        },
        newRepeatable(model, repeatable) {
            model.relations.repeatables.items.items.push(repeatable)
        },
        fieldWidth(field) {
            return field.width !== undefined ? `col-${field.width}` : 'col-12';
        },
    }
}
</script>

<style lang="css">
</style>
