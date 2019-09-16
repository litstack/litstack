<template>
    <fj-form-item :field="field" :model="model">

        <ckeditor
            :editor="editor"
            :config="editorConfig"
            :value="model[`${field.id}Model`]"
            @input="changed"/>

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
        },
    },
    methods: {
        changed(value) {
            this.model[`${this.field.id}Model`] = value
            this.$emit('changed')
        }
    },
    data() {
        return {
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
            },
        };
    },
};
</script>

<style lang="css"></style>
