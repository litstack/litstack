<template>

    <form class="row">
        <template v-for="m in preparedModels">
            <div
                :class="fieldWidth(field)"
                v-for="(field, index) in m.formFields">

                <base-formitem
                    v-if="
                        field.type ==
                            ('input' || 'number' || 'email')
                    "
                    :field="field">
                    <input
                        :type="field.type"
                        class="form-control"
                        :id="field.id"
                        :placeholder="field.placeholder"
                        v-model="field.model"
                    />
                    <div
                        class="input-group-append"
                        v-if="field.translatable">
                        <span class="input-group-text">
                            {{lng}}
                        </span>
                    </div>
                </base-formitem>
                <base-formitem
                    v-if="field.type == 'select'"
                    :field="field">
                    <select
                        class="form-control"
                        v-model="field.model">
                        <option
                            :value="option.value"
                            v-for="option in field.options">
                            {{ option.title }}
                        </option>
                    </select>
                </base-formitem>
                <base-formitem
                    v-if="field.type == 'textarea'"
                    :field="field">
                    <textarea
                        class="form-control"
                        :id="field.id"
                        :rows="field.rows"
                        :placeholder="field.placeholder"
                        v-model="field.model">
                    </textarea>
                </base-formitem>
                <base-formitem
                    v-if="field.type == 'wysiwyg'"
                    :field="field">
                    <ckeditor
                        :editor="editor"
                        :config="editorConfig"
                        v-model="field.model">
                    </ckeditor>
                    <div
                        class="input-group-append"
                        v-if="field.translatable">
                        <span class="input-group-text">
                            {{lng}}
                        </span>
                    </div>
                </base-formitem>
                <base-formitem
                    v-if="field.type == 'image'"
                    :field="field"
                >
                    <base-media
                        :field="field"
                        :id="m.id"
                        :model="m"
                        :media="m[field.id]"
                    />
                </base-formitem>
                <base-formitem
                    v-if="field.type == 'bool'"
                    :field="field">
                    <button
                        @click.prevent="toggle(field)"
                        class="btn btn-lg"
                    >
                        <i
                            class="fas fa-toggle-on text-success"
                            v-if="field.model"
                        ></i>
                        <i
                            class="fas fa-toggle-off text-danger"
                            v-else
                        ></i>
                    </button>
                </base-formitem>
                <FormBlock
                    v-if="field.type == 'block'"
                    :field="field"
                    :repeatables="m.relations.repeatables"
                    :model="m"
                    v-model="field.model"
                    @newRepeatable="(repeatable) => {newRepeatable(m, repeatable)}"
                />
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
import TestFormField from './TestFormField'
import Eloquent from './../../eloquent'
import EloquentCollection from './../../eloquent/collection'
import {mapGetters} from 'vuex'

export default {
    name: 'TestForm',
    components: {
        TestFormField
    },
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
