<template>
    <form class="row" style="margin-bottom: -1.5em;">
        <template v-for="m in preparedModels">
            <template v-for="(field, index) in m.form_fields">
                <div
                    :class="fieldWidth(field)"
                    v-if="ids.length < 1 || ids.includes(field.id)"
                >
                    <fj-form-input
                        v-if="field.type == 'input'"
                        :model="m"
                        :field="field"
                        @changed="changed(field, m)"
                    />

                    <fj-form-boolean
                        v-if="field.type == 'boolean'"
                        :model="m"
                        :field="field"
                        @changed="changed(field, m)"
                    />

                    <fj-form-checkboxes
                        v-if="field.type == 'checkboxes'"
                        :model="m"
                        :field="field"
                        @changed="changed(field, m)"
                    />

                    <fj-form-select
                        v-if="field.type == 'select'"
                        :field="field"
                        :model="m"
                        @changed="changed(field, m)"
                    />

                    <fj-form-textarea
                        v-if="field.type == 'textarea'"
                        :field="field"
                        :model="m"
                        @changed="changed(field, m)"
                    />

                    <fj-form-wysiwyg
                        v-if="field.type == 'wysiwyg'"
                        :field="field"
                        :model="m"
                        @changed="changed(field, m)"
                    />

                    <fj-form-code
                        v-if="field.type == 'code'"
                        :field="field"
                        :model="m"
                        @changed="changed(field, m)"
                    />

                    <fj-form-date-time
                        v-if="field.type == 'datetime' || field.type == 'dt'"
                        :field="field"
                        :model="m"
                        @changed="changed(field, m)"
                    />

                    <fj-form-media
                        v-if="field.type == 'image'"
                        :field="field"
                        :id="m.id"
                        :model="m"
                        :media="m[field.id]"
                    />

                    <fj-form-block
                        v-if="field.type == 'block' && m.id"
                        :field="field"
                        :repeatables="m.relations[field.id]"
                        :model="m"
                        @newRepeatable="
                            repeatable => {
                                newRepeatable(m, repeatable);
                            }
                        "
                    />

                    <fj-form-morph-one
                        v-if="field.type == 'morphOne'"
                        :field="field"
                        :model="m"
                    />

                    <template v-if="field.type == 'relation'">
                        <fj-form-has-many
                            v-if="field.many"
                            :form_field="field"
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
        </template>
    </form>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import FjordModel from './../../eloquent/fjord.model';
import EloquentCollection from './../../eloquent/collection';
import { mapGetters } from 'vuex';

export default {
    name: 'Form',
    props: {
        model: {
            type: Object,
            required: true
        },
        /*
        repeatables: {
            type: Object
        },
        */
        ids: {
            type: Array,
            default() {
                return [];
            }
        }
    },
    computed: {
        ...mapGetters(['lng'])
    },
    beforeMount() {
        if (this.model instanceof FjordModel) {
            this.preparedModels = [this.model];
        } else {
            this.preparedModels = this.model.items.items;
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
        };
    },
    methods: {
        changed(field, model) {
            if (model.getOriginalModel(field) == model[`${field.id}Model`]) {
                this.$store.commit('removeModelFromSave', {
                    model,
                    id: field.id
                });
            } else {
                this.$store.commit('addModelToSave', { model, id: field.id });
            }
        },
        toggle(field) {
            field.model = !field.model;
        },
        newRepeatable(model, repeatable) {
            model.relations.repeatables.items.items.push(repeatable);
        },
        fieldWidth(field) {
            return field.width !== undefined ? `col-${field.width}` : 'col-12';
        }
    }
};
</script>

<style lang="css"></style>
