<template>
    <fj-form-item :field="field">

        <ckeditor
            :editor="editor"
            :config="editorConfig"
            v-model="field.model"
            @input="$emit('changed')"/>

        <fj-form-language :field="field"/>

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
