<template>
    <BaseFormitem :field="item">
        <ckeditor
            :editor="editor"
            :config="editorConfig"
            v-model="model"
        ></ckeditor>
        <slot />
    </BaseFormitem>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    name: 'FormWysiwyg',
    props: {
        item: {
            type: Object,
            required: true
        },
        value: {
            required: true
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
            model: null
        };
    },
    beforeMount() {
        this.init();
    },
    methods: {
        init() {
            this.model = this.value;
        }
    },
    watch: {
        model(val) {
            this.$emit('input', val);
        },
        value(val) {
            if (val == null) {
                this.model = '';
            } else {
                this.init();
            }
        }
    }
};
</script>

<style lang="css"></style>
