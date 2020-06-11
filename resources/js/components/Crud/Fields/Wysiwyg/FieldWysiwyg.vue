<template>
    <fj-base-field
        :field="field"
        :model="model"
        v-slot:default="{ state }"
        v-on="$listeners"
    >
        <template v-if="!field.readonly">
            <div
                class="fj-field-wysiwyg"
                :class="state === false ? 'form-control is-invalid' : ''"
            >
                <editor-menu-bar
                    :editor="editor"
                    v-slot="{ commands, isActive, getMarkAttrs }"
                >
                    <div class="fj-field-wysiwyg__menu">
                        <b-dropdown
                            :text="format(isActive)"
                            variant="outline-secondary"
                            size="sm"
                        >
                            <b-dropdown-item
                                :active="isActive.paragraph()"
                                @click="commands.paragraph"
                            >
                                Paragraph
                            </b-dropdown-item>

                            <b-dropdown-item
                                :active="isActive.heading({ level: 2 })"
                                @click="commands.heading({ level: 2 })"
                            >
                                <h2>
                                    H2
                                </h2>
                            </b-dropdown-item>

                            <b-dropdown-item
                                :active="isActive.heading({ level: 3 })"
                                @click="commands.heading({ level: 3 })"
                            >
                                <h3>
                                    H3
                                </h3>
                            </b-dropdown-item>
                            <b-dropdown-item
                                :active="isActive.heading({ level: 4 })"
                                @click="commands.heading({ level: 4 })"
                            >
                                <h4>
                                    H4
                                </h4>
                            </b-dropdown-item>
                        </b-dropdown>
                        <b-button
                            :variant="
                                isActive.bold() ? 'primary' : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.bold"
                        >
                            <fa-icon icon="bold" />
                        </b-button>
                        <b-button
                            :variant="
                                isActive.italic() ? 'primary' : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.italic"
                        >
                            <fa-icon icon="italic" />
                        </b-button>

                        <b-button
                            :variant="
                                isActive.strike() ? 'primary' : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.strike"
                        >
                            <fa-icon icon="strikethrough" />
                        </b-button>

                        <b-button
                            :variant="
                                isActive.underline() ? 'primary' : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.underline"
                        >
                            <fa-icon icon="underline" />
                        </b-button>

                        <b-button
                            :variant="
                                isActive.bullet_list()
                                    ? 'primary'
                                    : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.bullet_list"
                        >
                            <fa-icon icon="list-ul" />
                        </b-button>

                        <b-button
                            :variant="
                                isActive.ordered_list()
                                    ? 'primary'
                                    : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.ordered_list"
                        >
                            <fa-icon icon="list-ol" />
                        </b-button>

                        <b-button
                            :variant="
                                isActive.blockquote()
                                    ? 'primary'
                                    : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.blockquote"
                        >
                            <fa-icon icon="quote-right" />
                        </b-button>

                        <b-dropdown
                            class="dropdown-sm-square"
                            dropbottom
                            no-caret
                            :variant="
                                isActive.custom_link()
                                    ? 'primary'
                                    : 'transparent'
                            "
                            size="sm"
                            @show="showLinkMenu(getMarkAttrs('custom_link'))"
                        >
                            <template v-slot:button-content>
                                <fa-icon icon="link" />
                            </template>
                            <b-dropdown-form style="min-width: 340px;">
                                <b-form-input
                                    v-model="linkUrl"
                                    placeholder="Enter link"
                                    size="sm"
                                    class="mb-2"
                                ></b-form-input>
                                <b-checkbox
                                    v-model="target"
                                    value="_blank"
                                    unchecked-value="_self"
                                    class="mb-2"
                                >
                                    <small>
                                        {{
                                            trans(
                                                'crud.fields.wysiwyg.new_window'
                                            )
                                        }}
                                    </small>
                                </b-checkbox>
                                <b-button
                                    class="mt-1 float-right"
                                    variant="primary"
                                    size="sm"
                                    @click="setLinkUrl(commands.custom_link)"
                                >
                                    {{ trans('fj.save') }}
                                </b-button>
                            </b-dropdown-form>
                        </b-dropdown>

                        <b-dropdown
                            v-if="field.colors"
                            class="dropdown-sm-square"
                            dropbottom
                            no-caret
                            :variant="
                                isActive.font_color()
                                    ? 'primary'
                                    : 'transparent'
                            "
                            size="sm"
                        >
                            <template v-slot:button-content>
                                <fa-icon icon="palette" />
                            </template>
                            <b-dropdown-form style="min-width: 340px;">
                                <v-swatches
                                    v-model="fontColor"
                                    :swatches="swatches"
                                    inline
                                    @input="setFontColor(commands.font_color)"
                                ></v-swatches>
                            </b-dropdown-form>
                        </b-dropdown>

                        <b-button
                            variant="outline-secondary"
                            size="sm"
                            class="btn-square"
                            @click="commands.undo"
                        >
                            <fa-icon icon="undo" />
                        </b-button>

                        <b-button
                            variant="outline-secondary"
                            size="sm"
                            class="btn-square"
                            @click="commands.redo"
                        >
                            <fa-icon icon="redo" />
                        </b-button>
                    </div>
                </editor-menu-bar>

                <editor-content
                    :editor="editor"
                    class="fj-field-wysiwyg__content"
                />
            </div>
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
    </fj-base-field>
</template>

<script>
import { Editor, EditorContent, EditorMenuBar } from 'tiptap';
import {
    Blockquote,
    CodeBlock,
    HardBreak,
    Heading,
    HorizontalRule,
    OrderedList,
    BulletList,
    ListItem,
    TodoItem,
    TodoList,
    Bold,
    Code,
    Italic,
    Strike,
    Underline,
    History
} from 'tiptap-extensions';
import CustomLink from './Nodes/CustomLink';
import FontColor from './Nodes/FontColor';

import VSwatches from 'vue-swatches';
import 'vue-swatches/dist/vue-swatches.css';

export default {
    name: 'FieldWysiwyg',
    components: {
        EditorContent,
        EditorMenuBar,
        VSwatches
    },
    props: {
        field: {
            type: Object,
            required: true
        },
        model: {
            required: true,
            type: Object
        },
        value: {
            required: true
        }
    },
    data() {
        return {
            editor: new Editor({
                extensions: [
                    new Blockquote(),
                    new CodeBlock(),
                    new HardBreak(),
                    new Heading({ levels: [2, 3, 4] }),
                    new HorizontalRule(),
                    new BulletList(),
                    new OrderedList(),
                    new ListItem(),
                    new TodoItem(),
                    new TodoList(),
                    new Bold(),
                    new Code(),
                    new Italic(),
                    new CustomLink(),
                    new Strike(),
                    new Underline(),
                    new History(),
                    new FontColor()
                ]
            }),

            linkUrl: null,
            target: null,
            fontColor: null,
            swatches: []
        };
    },
    beforeMount() {
        this.editor.setContent(this.value);

        // set font colors
        if (this.field.colors) {
            this.swatches = this.field.colors;
        }
    },
    mounted() {
        this.editor.on('update', ({ getHTML }) => {
            this.$emit('input', getHTML());
        });

        Fjord.bus.$on('languageChanged', () => {
            this.$nextTick(() => {
                this.editor.setContent(this.value);
            });
        });
    },
    beforeDestroy() {
        this.editor.destroy();
    },
    methods: {
        format(isActive) {
            if (isActive.paragraph()) {
                return 'Paragraph';
            }
            if (isActive.heading({ level: 2 })) {
                return 'H2';
            }
            if (isActive.heading({ level: 3 })) {
                return 'H3';
            }
            if (isActive.heading({ level: 4 })) {
                return 'H4';
            }
        },
        showLinkMenu(attrs) {
            this.linkUrl = attrs.href;
            this.target = attrs.target;
        },
        setLinkUrl(command) {
            command({ href: this.linkUrl, target: this.target });
            this.linkUrl = null;
            this.target = null;
        },
        setFontColor(command) {
            command({ style: `color: ${this.fontColor}` });
            this.fontColor = null;
        }
    }
};
</script>
<style lang="scss">
@import '@fj-sass/_variables';
.fj-field-wysiwyg {
    width: 100%;
    border-radius: $border-radius;
    border: 1px solid $border-color;
    background: white;
    min-height: $input-height;

    padding: $input-padding-x;
    padding-bottom: 0;
    &__content {
        padding-top: $input-padding-x;
        .ProseMirror {
            padding-bottom: 1px;
            &:focus {
                outline: none;
            }
        }
        p {
            line-height: 1.25rem;
            font-size: $input-font-size;
        }
    }

    .dropdown-sm-square.show {
        .dropdown-toggle {
            background: $secondary;
            color: white;
        }
    }
}
</style>
