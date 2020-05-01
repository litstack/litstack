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

/**
 * CKEditor Theme variables.
 *
 * https://ckeditor.com/docs/ckeditor5/latest/framework/guides/deep-dive/ui/theme-customization.html
 */
:root {
    /* Overrides the border radius setting in the theme. */
    --ck-border-radius: #{$input-border-radius};

    /* Overrides the default font size in the theme. */
    --ck-font-size-base: #{$input-font-size};

    /* Helper variables to avoid duplication in the colors. */
    --ck-custom-background: #{$input-bg};
    //--ck-custom-foreground: hsl(255, 3%, 18%);
    --ck-custom-border: #{$input-border-color};
    //--ck-custom-white: hsl(0, 0%, 100%);

    --ck-color-base-active: #{$primary};
    --ck-color-base-active-focus: var(--ck-color-base-active);

    --ck-spacing-standard: #{$input-padding-x-sm};
    --ck-spacing-small: #{$input-padding-y-sm};
    --ck-spacing-tiny: #{$input-padding-y-sm};
    --ck-color-base-border: var(--ck-custom-border);

    /* -- Overrides generic colors. ------------------------------------------------------------- */

    --ck-color-base-foreground: var(--ck-custom-background);
    //--ck-color-focus-border: hsl(208, 90%, 62%);
    //--ck-color-text: hsl(0, 0%, 98%);
    //--ck-color-shadow-drop: hsla(0, 0%, 0%, 0.2);
    //--ck-color-shadow-inner: hsla(0, 0%, 0%, 0.1);

    /* -- Overrides the default .ck-button class colors. ---------------------------------------- */

    --ck-color-button-default-background: var(--ck-custom-background);
    //--ck-color-button-default-hover-background: hsl(270, 1%, 22%);
    --ck-color-button-default-active-background: $secondary;
    --ck-color-button-default-active-shadow: transparent;
    --ck-color-button-default-disabled-background: var(--ck-custom-background);

    //--ck-color-button-on-background: var(--ck-custom-foreground);
    //--ck-color-button-on-hover-background: hsl(255, 4%, 16%);
    --ck-color-button-on-active-background: $secondary;
    //--ck-color-button-on-active-shadow: hsl(240, 3%, 19%);
    //--ck-color-button-on-disabled-background: var(--ck-custom-foreground);

    //--ck-color-button-action-background: hsl(168, 76%, 42%);
    //--ck-color-button-action-hover-background: hsl(168, 76%, 38%);
    //--ck-color-button-action-active-background: hsl(168, 76%, 36%);
    //--ck-color-button-action-active-shadow: hsl(168, 75%, 34%);
    //--ck-color-button-action-disabled-background: hsl(168, 76%, 42%);
    //--ck-color-button-action-text: var(--ck-custom-white);

    //--ck-color-button-save: hsl(120, 100%, 46%);
    //--ck-color-button-cancel: hsl(15, 100%, 56%);

    /* -- Overrides the default .ck-dropdown class colors. -------------------------------------- */

    --ck-color-dropdown-panel-background: var(--ck-custom-background);
    //--ck-color-dropdown-panel-border: var(--ck-custom-foreground);

    /* -- Overrides the default .ck-splitbutton class colors. ----------------------------------- */

    //--ck-color-split-button-hover-background: var(
    //--ck-color-button-default-hover-background
    //);
    //--ck-color-split-button-hover-border: var(--ck-custom-foreground);

    /* -- Overrides the default .ck-input class colors. ----------------------------------------- */

    //--ck-color-input-background: var(--ck-custom-foreground);
    //--ck-color-input-border: hsl(257, 3%, 43%);
    //--ck-color-input-text: hsl(0, 0%, 98%);
    //--ck-color-input-disabled-background: hsl(255, 4%, 21%);
    //--ck-color-input-disabled-border: hsl(250, 3%, 38%);
    //--ck-color-input-disabled-text: hsl(0, 0%, 46%);

    /* -- Overrides the default .ck-list class colors. ------------------------------------------ */

    --ck-color-list-background: var(--ck-custom-background);
    //--ck-color-list-button-hover-background: var(--ck-color-base-foreground);
    --ck-color-list-button-on-background: var(--ck-color-base-active);
    --ck-color-list-button-on-background-focus: var(
        --ck-color-base-active-focus
    );
    //--ck-color-list-button-on-text: var(--ck-color-base-background);

    /* -- Overrides the default .ck-balloon-panel class colors. --------------------------------- */

    --ck-color-panel-background: var(--ck-custom-background);
    --ck-color-panel-border: var(--ck-custom-border);

    /* -- Overrides the default .ck-toolbar class colors. --------------------------------------- */

    --ck-color-toolbar-background: var(--ck-custom-background);
    --ck-color-toolbar-border: var(--ck-custom-border);

    /* -- Overrides the default .ck-tooltip class colors. --------------------------------------- */

    //--ck-color-tooltip-background: hsl(252, 7%, 14%);
    //--ck-color-tooltip-text: hsl(0, 0%, 93%);

    /* -- Overrides the default colors used by the ckeditor5-image package. --------------------- */

    //--ck-color-image-caption-background: hsl(0, 0%, 97%);
    //--ck-color-image-caption-text: hsl(0, 0%, 20%);

    /* -- Overrides the default colors used by the ckeditor5-widget package. -------------------- */

    //--ck-color-widget-blurred-border: hsl(0, 0%, 87%);
    //--ck-color-widget-hover-border: hsl(43, 100%, 68%);
    //--ck-color-widget-editable-focus-background: var(--ck-custom-white);

    /* -- Overrides the default colors used by the ckeditor5-link package. ---------------------- */

    //--ck-color-link-default: hsl(190, 100%, 75%);
}

.ck-editor {
    width: calc(100%);
    min-width: calc(100%);
}

.ck-content {
    padding: $input-padding-y $input-padding-x !important;
}

.ck.ck-toolbar {
    padding-left: ($input-padding-x - $input-padding-x-sm) !important;
    padding-right: ($input-padding-x - $input-padding-x-sm) !important;
    padding-top: ($input-padding-y - $input-padding-y-sm) !important;
    padding-bottom: ($input-padding-y - $input-padding-y-sm) !important;

    &__separator {
        margin-left: ($input-padding-x - $input-padding-x-sm) !important;
        margin-right: ($input-padding-x - $input-padding-x-sm) !important;
        margin-top: -($input-padding-y - $input-padding-y-sm) !important;
        margin-bottom: -($input-padding-y - $input-padding-y-sm) !important;
    }

    > * {
        margin: 0 map-get($spacers, 2) 0 0;
    }

    > div:first-child {
        &.ck-button,
        > .ck-button {
            border-top-left-radius: var(--ck-border-radius) !important;
        }
    }

    .ck.ck-button:not(.ck-disabled):hover,
    a.ck.ck-button:not(.ck-disabled):hover {
        cursor: pointer;
        background: transparent;
        border: $border-width solid $secondary;
    }
    .ck.ck-button.ck-on:not(.ck-disabled),
    a.ck.ck-button.ck-on:not(.ck-disabled) {
        background: $secondary;
        color: $white;

        &:hover {
            background: $secondary;
            color: $white;
        }
    }
}

.ck.ck-button.ck-button_with-text,
a.ck.ck-button.ck-button_with-text {
    //margin: -$input-btn-padding-y-sm -$input-btn-padding-x-sm;
}
.ck.ck-button {
    //margin: -$input-btn-padding-y-sm;
}

.ck.ck-editor__editable:not(.ck-editor__nested-editable).ck-focused {
    @include form-control-focus($ignore-warning: true);
}

.ck-rounded-corners
    .ck.ck-dropdown
    .ck-dropdown__panel
    .ck-list
    .ck-list__item:first-child
    .ck-button,
.ck.ck-dropdown
    .ck-dropdown__panel
    .ck-list
    .ck-list__item:first-child
    .ck-button.ck-rounded-corners,
.ck-rounded-corners .ck.ck-dropdown .ck-dropdown__panel .ck-list,
.ck.ck-dropdown .ck-dropdown__panel .ck-list.ck-rounded-corners,
.ck-rounded-corners .ck.ck-dropdown__panel,
.ck.ck-dropdown__panel.ck-rounded-corners {
    border-top-left-radius: var(--ck-border-radius) !important;
}

/*
.ck
.ck.ck-toolbar {
    border: $border-width solid $border-color !important;
    border-top-left-radius: $input-border-radius !important;
    border-top-right-radius: $input-border-radius !important;
    padding: 0 !important;
    & > * {
        margin: 0 !important;
        > .ck-button:first-child {
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
