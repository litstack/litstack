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
<style lang="scss">
@import '@fj-sass/_variables';

.ck.ck-editor {
    width: calc(100%);
    min-width: calc(100%);
}
.ck.ck-toolbar {
    border: $border-width solid $border-color !important;
    border-top-left-radius: $input-border-radius !important;
    border-top-right-radius: $input-border-radius !important;
    padding: 0 !important;
    & > * {
        margin: 0 !important;
        .ck-button:first-child {
            border-top-left-radius: $input-border-radius !important;
        }
    }
}
.ck.ck-editor__main > .ck-editor__editable {
    border: $border-width solid $border-color !important;
    border-top: none !important;
    border-bottom-left-radius: $input-border-radius !important;
    border-bottom-right-radius: $input-border-radius !important;
}
.cl.ck-dropdown {
    padding: 0 !important;
    margin: 0 !important;
    border-bottom-left-radius: $input-border-radius !important;
}
.ck.ck-button {
    padding: $btn-padding-y-sm $btn-padding-x-sm !important;
    height: 38px !important;
}
/*
    &.ck-editor {
        display: block;
        width: 100%;
        height: $input-height;
        padding: $input-padding-y $input-padding-x;
        font-family: $input-font-family;
        @include font-size($input-font-size);
        font-weight: $input-font-weight;
        line-height: $input-line-height;
        color: $input-color;
        background-color: $input-bg;
        background-clip: padding-box;
        border: $input-border-width solid $input-border-color;

        // Note: This has no effect on <select>s in some browsers, due to the limited stylability of `<select>`s in CSS.
        @include border-radius($input-border-radius, 0);

        @include box-shadow($input-box-shadow);
        @include transition($input-transition);
    }
    */
</style>
