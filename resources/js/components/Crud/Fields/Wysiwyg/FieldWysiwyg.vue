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
        },
        defaultFormats() {
            return [
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
                },
                {
                    model: 'Custom',
                    view: {
                        name: 'span',
                        classes: 'h1'
                    },
                    title: 'Custom',

                    converterPriority: 'high'
                }
            ];
        }
    },
    beforeMount() {
        this.init();
    },
    data() {
        return {
            value: false,
            original: false,
            editor: ClassicEditor,
            editorConfig: {
                heading: {
                    options: this.field.formats
                        ? this.field.formats
                        : this.defaultFormats()
                },
                toolbar: {
                    items: this.field.toolbar,
                    shouldGroupWhenFull: true
                }
            }
        };
    }
};
</script>
<style lang="scss">
@import '@fj-sass/_variables';
.ck.ck-editor {
    width: 100%;
}
.ck-rounded-corners .ck.ck-editor__top .ck-sticky-panel .ck-toolbar,
.ck.ck-editor__top .ck-sticky-panel .ck-toolbar.ck-rounded-corners {
    border-top-left-radius: $border-radius;
    border-top-right-radius: $border-radius;
    border-color: $border-color;
    background: white;
    height: $input-height;
}

.ck-rounded-corners .ck.ck-editor__main > .ck-editor__editable,
.ck.ck-editor__main > .ck-editor__editable.ck-rounded-corners {
    border-bottom-left-radius: $border-radius;
    border-bottom-right-radius: $border-radius;
    border-color: $border-color;
    padding: 0.25rem 0.75rem;
}

.ck.ck-button:not(.ck-disabled):hover,
a.ck.ck-button:not(.ck-disabled):hover {
    border: 1px solid $secondary;
    background: white;
}

// color of icons
.ck.ck-icon :not([fill]) {
    fill: $secondary;
}
.ck.ck-button {
    &:hover {
        .ck.ck-icon :not([fill]) {
            //fill: white;
        }
    }
}
.ck.ck-button.ck-on,
a.ck.ck-button.ck-on {
    background: $secondary;
    .ck.ck-icon :not([fill]) {
        fill: white;
    }
}
.ck.ck-icon {
    transform: scale(0.9);
}

.ck-rounded-corners .ck.ck-button,
.ck-rounded-corners a.ck.ck-button,
.ck.ck-button.ck-rounded-corners,
a.ck.ck-button.ck-rounded-corners {
    border-radius: $border-radius-sm;
}

// format button font color
.ck.ck-dropdown .ck-button.ck-dropdown__button {
    color: $secondary;
    font-family: 'Inter';
    &:hover {
        color: $secondary;
    }
}

.ck.ck-toolbar__separator {
    background: $secondary;
}

.ck.ck-editor__editable:not(.ck-editor__nested-editable).ck-focused {
    border-color: $input-btn-focus-color;
    box-shadow: $input-btn-focus-box-shadow;
}

// dropdown
//
.ck.ck-button.ck-on.ck.ck-dropdown .ck-button.ck-dropdown__button {
    background: white;
}
.ck.ck-dropdown .ck-button.ck-dropdown__button {
    border: 1px solid transparent !important;
    outline: none;
    background: white !important;
    box-shadow: none !important;
    &:hover,
    &.ck-on {
        cursor: pointer;
        border: 1px solid $secondary !important;
    }
}
.ck.ck-dropdown .ck-dropdown__panel.ck-dropdown__panel_ne,
.ck.ck-dropdown .ck-dropdown__panel.ck-dropdown__panel_se {
    .ck.ck-button:not(.ck-disabled):hover,
    a.ck.ck-button:not(.ck-disabled):hover {
        border: none;
        background: $gray-300;
    }
}
.ck.ck-dropdown .ck-button.ck-dropdown__button.ck-on {
    border-radius: $border-radius-sm;
}

.ck.ck-dropdown .ck-button.ck-dropdown__button.ck-on .ck.ck-icon :not([fill]),
a.ck.ck-dropdown .ck-button.ck-dropdown__button.ck-on .ck.ck-icon :not([fill]) {
    fill: $secondary;
}
.ck.ck-dropdown .ck-dropdown__panel.ck-dropdown__panel_se {
    border-radius: $border-radius;
    border: none;
    box-shadow: $dropdown-shadow;
    & > ul {
        border-radius: $border-radius !important;
        overflow: hidden;
    }
}

.ck.ck-list__item .ck-button.ck-on {
    background: white;
    color: $secondary;
}
// active dropdown item
.ck.ck-list__item .ck-button.ck-on {
    background: $primary;
    color: white;
    &:hover {
        color: $secondary;
    }
}
</style>
