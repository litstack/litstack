<template>
    <fj-form-item :field="field" :model="model" :value="value">
        <ckeditor
            :editor="editor"
            :config="editorConfig"
            :value="model[`${field.id}Model`]"
            @input="changed"
        />

        <slot />
    </fj-form-item>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    name: 'FormWysiwyg',
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
        changed(value) {
            this.value = value.rawText();
            this.model[`${this.field.id}Model`] = value;
            this.$emit('changed');
        }
    },
    data() {
        return {
            value:
                this.model[`${this.field.id}Model`] == null
                    ? this.model[`${this.field.id}Model`]
                    : this.model[`${this.field.id}Model`].rawText(),
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

<style lang="css"></style>
