<template>
    <fj-base-field
        :field="field"
        :model="model"
        v-slot:default="{ state }"
        v-on="$listeners"
    >
        <div class="fj-field-wysiwyg__css" v-if="field.css">
            <v-style v-html="prependCssSelectors(field.css)"></v-style>
        </div>

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
                            class="fj-field-wysiwyg__menu-dropdown"
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
                            class="dropdown-sm-square fj-color-palette"
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
                            <div class="fj-color-palette-wrapper">
                                <b-button
                                    size="sm"
                                    class="btn-square"
                                    v-for="color in fontColors"
                                    :key="color"
                                    @click="
                                        setFontColor(commands.font_color, color)
                                    "
                                    :style="
                                        `background: ${color}; border-color: ${color}`
                                    "
                                ></b-button>
                            </div>
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
                    :id="identifier"
                />
            </div>
        </template>
        <template v-else>
            <div v-html="value"></div>
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

export default {
    name: 'FieldWysiwyg',
    components: {
        EditorContent,
        EditorMenuBar,
        'v-style': {
            render: function(createElement) {
                return createElement('style', this.$slots.default);
            }
        }
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
            fontColors: []
        };
    },
    beforeMount() {
        this.editor.setContent(this.value);

        // set font colors
        if (this.field.colors) {
            this.fontColors = this.field.colors;
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
        setFontColor(command, color) {
            command({ style: `color: ${color}` });
        },
        prependCssSelectors(css) {
            // Prepend every css-selector with the identifier of the editor
            // credit: Ryan Worth
            // https://stackoverflow.com/questions/11161198/prepend-all-css-selectors
            return css.replace(
                /(^(?:\s|[^@{])*?|[},]\s*)(\/\/.*\s+|.*\/\*[^*]*\*\/\s*|@media.*{\s*|@font-face.*{\s*)*([.#]?-?[_a-zA-Z]+[_a-zA-Z0-9-]*)(?=[^}]*{)/g,
                `$1$2 #${this.identifier} $3`
            );
        }
    },
    computed: {
        identifier() {
            return `${this.field.local_key}-${this.field.route_prefix.replace(
                /\//g,
                '-'
            )}`;
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
    &__menu {
        display: grid;
        grid-template-columns: repeat(auto-fill, 1.75rem);
        grid-auto-rows: 1fr;
        grid-gap: 0.5rem;
        &-dropdown {
            grid-column: 1 / span 5 !important;
        }
    }
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
    .fj-color-palette {
        .dropdown-menu {
            width: 14rem;
        }
        &-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fill, 1.75rem);
            grid-auto-rows: 1fr;
            grid-gap: 0.5rem;
            padding: 0 0.5rem;
        }
    }
}
</style>
