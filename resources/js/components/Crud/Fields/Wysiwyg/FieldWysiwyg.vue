<template>
    <fj-form-item :field="field" :model="model" :value="value">
        <template v-if="!field.readonly">
            <ckeditor
                :editor="editor"
                :config="editorConfig"
                :value="value"
                @input="changed"
            />
        </template>
        <template v-else>
            <div class="form-control" style="height: auto;" readonly>
                <div
                    v-html="model[`${field.id}Model`]"
                    class="ck-blurred ck ck-content ck-editor__editable ck-rounded-corners ck-editor__editable_inline"
                ></div>
            </div>
        </template>

        <slot />
    </fj-form-item>
</template>

<script>
import methods from '../methods';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    name: 'FieldWysiwyg',
    props: {
        field: {
            type: Object,
            required: true
        },
        model: {
            required: true,
            type: Object
        }
    },
    methods: {
        ...methods,
        changed(value) {
            this.setValue(value);
            this.$emit('changed', value);
        }
    },
    beforeMount() {
        this.init();
    },
    data() {
        return {
            value: null,
            editor: ClassicEditor,
            editorConfig: {
                removePlugins: [],
                heading: {
                    options: [
                        {
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'p'
                        },
                        {
                            model: 'Headline 2',
                            view: {
                                name: 'h2',
                                classes: 'h2'
                            },
                            title: 'Headline 2',
                            class: 'h2',

                            converterPriority: 'high'
                        },
                        {
                            model: 'Headline 3',
                            view: {
                                name: 'h3',
                                classes: 'h3'
                            },
                            title: 'Headline 3',
                            class: 'h3',

                            converterPriority: 'high'
                        },
                        {
                            model: 'Headline 4',
                            view: {
                                name: 'h4',
                                classes: 'h4'
                            },
                            title: 'Headline 4',
                            class: 'h4',

                            converterPriority: 'high'
                        }
                    ]
                },
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
    }
};
</script>
