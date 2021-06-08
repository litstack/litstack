<template>
    <lit-base-field
        :field="field"
        :model="model"
        v-slot:default="{ state }"
        v-on="$listeners"
    >
        <div class="lit-field-wysiwyg__css" v-if="field.css">
            <v-style v-html="prependCssSelectors(field.css)"></v-style>
        </div>

        <template v-if="!field.readonly">
            <div
                class="lit-field-wysiwyg"
                :class="state === false ? 'form-control is-invalid' : ''"
                v-if="editor"
            >
                <editor-menu-bar
                    :editor="editor"
                    v-slot="{ commands, isActive, getMarkAttrs }"
                >
                    <div class="lit-field-wysiwyg__menu">
                        <b-dropdown
                            v-if="hasControl('format')"
                            :text="format(isActive)"
                            variant="outline-secondary"
                            size="sm"
                            class="lit-field-wysiwyg__menu-dropdown"
                        >
                            <b-dropdown-item
                                :active="isActive.paragraph()"
                                @click="commands.paragraph"
                            >
                                Paragraph
                            </b-dropdown-item>

                            <b-dropdown-item
                                v-for="level in headingLevels"
                                :key="level"
                                :active="isActive.heading({ level })"
                                @click="commands.heading({ level })"
                            >
                                <component :is="`h${level}`">
                                    {{ `H${level}` }}
                                </component>
                            </b-dropdown-item>
                        </b-dropdown>
                        <b-button
                            v-if="hasControl('bold')"
                            :variant="
                                isActive.bold() ? 'primary' : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.bold"
                        >
                            <lit-fa-icon icon="bold" />
                        </b-button>
                        <b-button
                            v-if="hasControl('italic')"
                            :variant="
                                isActive.italic() ? 'primary' : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.italic"
                        >
                            <lit-fa-icon icon="italic" />
                        </b-button>

                        <b-button
                            v-if="hasControl('strike')"
                            :variant="
                                isActive.strike() ? 'primary' : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.strike"
                        >
                            <lit-fa-icon icon="strikethrough" />
                        </b-button>

                        <b-button
                            v-if="hasControl('underline')"
                            :variant="
                                isActive.underline() ? 'primary' : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.underline"
                        >
                            <lit-fa-icon icon="underline" />
                        </b-button>

                        <b-button
                            v-if="hasControl('bullet_list')"
                            :variant="
                                isActive.bullet_list()
                                    ? 'primary'
                                    : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.bullet_list"
                        >
                            <lit-fa-icon icon="list-ul" />
                        </b-button>

                        <b-button
                            v-if="hasControl('ordered_list')"
                            :variant="
                                isActive.ordered_list()
                                    ? 'primary'
                                    : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.ordered_list"
                        >
                            <lit-fa-icon icon="list-ol" />
                        </b-button>

                        <b-button
                            v-if="hasControl('blockquote')"
                            :variant="
                                isActive.blockquote()
                                    ? 'primary'
                                    : 'transparent'
                            "
                            size="sm"
                            class="btn-square"
                            @click="commands.blockquote"
                        >
                            <lit-fa-icon icon="quote-right" />
                        </b-button>

                        <b-dropdown
                            v-if="hasControl('href')"
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
                                <lit-fa-icon icon="link" />
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
                                            __('crud.fields.wysiwyg.new_window')
                                        }}
                                    </small>
                                </b-checkbox>
                                <b-button
                                    class="float-right mt-1"
                                    variant="primary"
                                    size="sm"
                                    @click="setLinkUrl(commands.custom_link)"
                                >
                                    {{ __('base.save') }}
                                </b-button>
                            </b-dropdown-form>
                        </b-dropdown>

                        <b-dropdown
                            v-if="colors && hasControl('colors')"
                            class="dropdown-sm-square lit-color-palette"
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
                                <lit-fa-icon icon="palette" />
                            </template>
                            <div class="lit-color-palette-wrapper">
                                <b-button
                                    size="sm"
                                    class="btn-square"
                                    v-for="color in colors"
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

                        <lit-field-wysiwyg-table
                            v-if="hasControl('table')"
                            :is-active="isActive"
                            :commands="commands"
                            :field="field"
                        />

                        <b-button
                            variant="outline-secondary"
                            size="sm"
                            class="btn-square"
                            @click="commands.undo"
                        >
                            <lit-fa-icon icon="undo" />
                        </b-button>

                        <b-button
                            variant="outline-secondary"
                            size="sm"
                            class="btn-square"
                            @click="commands.redo"
                        >
                            <lit-fa-icon icon="redo" />
                        </b-button>

                        <b-button
                            class="btn-square"
                            size="sm"
                            :variant="editRaw ? 'primary' : 'outline-secondary'"
                            @click="toggleHtmlView()"
                        >
                            <lit-fa-icon icon="code" />
                        </b-button>
                    </div>
                </editor-menu-bar>

                <editor-content
                    :editor="editor"
                    class="lit-field-wysiwyg__content"
                    :id="identifier"
                    v-if="!editRaw"
                />

                <b-form-textarea
                    class="lit-field-wysiwyg_raw"
                    v-model="valueCopy"
                    v-if="editRaw"
                    rows="3"
                ></b-form-textarea>
            </div>
        </template>
        <template v-else>
            <div v-html="value"></div>
        </template>

        <slot />
    </lit-base-field>
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
    History,
    Table,
    TableHeader,
    TableCell,
    TableRow,
} from 'tiptap-extensions';
import CustomLink from './Nodes/CustomLink';
import FontColor from './Nodes/FontColor';
import { mapGetters } from 'vuex';

export default {
    name: 'FieldWysiwyg',
    components: {
        EditorContent,
        EditorMenuBar,
        'v-style': {
            render: function(createElement) {
                return createElement('style', this.$slots.default);
            },
        },
    },
    props: {
        field: {
            type: Object,
            required: true,
        },
        model: {
            required: true,
            type: Object,
        },
        value: {
            required: true,
        },
    },
    data() {
        return {
            editor: null,
            editRaw: false,
            linkUrl: null,
            target: null,
            valueCopy: _.clone(this.value),
        };
    },
    beforeMount() {
        this.init();
    },
    mounted() {
        this.editor.setContent(this.value);

        this.editor.on('update', ({ getHTML }) => {
            this.$emit('input', getHTML());
            this.valueCopy = _.clone(getHTML());
        });

        Lit.bus.$on('languageChanged', () => {
            this.$nextTick(() => {
                this.valueCopy = _.clone(this.value);
                this.editor.setContent(this.value);
            });
        });
    },
    beforeDestroy() {
        this.editor.destroy();
    },
    watch: {
        valueCopy(val) {
            if (this.editRaw) {
                let data = this.stripScripts(val);
                this.editor.setContent(data);
                this.$emit('input', data);
            }
        },
    },
    methods: {
        init() {
            this.editor = new Editor({
                disablePasteRules: !this.field.enablePasteRules,
                disableInputRules: !this.field.enableInputRules,
                extensions: [
                    new Blockquote(),
                    new CodeBlock(),
                    new HardBreak(),
                    new Heading({ levels: this.headingLevels }),
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
                    new FontColor(),
                    new Table({ resizable: true }),
                    new TableHeader(),
                    new TableCell(),
                    new TableRow(),
                ],
            });
        },
        // credits:
        // https://stackoverflow.com/questions/6659351/removing-all-script-tags-from-html-with-js-regular-expression/6660151
        stripScripts(s) {
            var div = document.createElement('div');
            div.innerHTML = s;
            var scripts = div.getElementsByTagName('script');
            var i = scripts.length;
            while (i--) {
                scripts[i].parentNode.removeChild(scripts[i]);
            }
            return div.innerHTML;
        },
        format(isActive) {
            if (isActive.paragraph()) {
                return 'Paragraph';
            }

            for (let index = 0; index < this.headingLevels.length; index++) {
                const el = this.headingLevels[index];
                if (isActive.heading({ level: el })) {
                    return `H${el}`;
                }
            }
        },
        hasControl(control) {
            if (this.field.only) {
                return _.includes(this.field.only, control);
            }
            return _.includes(this.lit_config.fields.wysiwyg.controls, control);
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
        },
        toggleHtmlView() {
            this.editRaw = !this.editRaw;
        },
    },
    computed: {
        ...mapGetters(['lit_config']),
        identifier() {
            return `${this.field.local_key}-${this.field.route_prefix.replace(
                /\//g,
                '-'
            )}`;
        },
        headingLevels() {
            let levels =
                this.lit_config?.fields?.wysiwyg?.headingLevels || null;

            return levels || [2, 3, 4];
        },
        colors() {
            return (
                this.field.colors ||
                this.lit_config?.fields?.wysiwyg?.colors ||
                null
            );
        },
    },
};
</script>
<style lang="scss">
@import '@lit-sass/_variables';
.lit-field-wysiwyg {
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
        table,
        th,
        td {
            border: 1px solid $border-color;
        }
        table {
            border-radius: 15px;
            margin-bottom: 1rem;
        }
        th,
        td {
            min-width: 2rem;
            padding: 0.5rem;
            p {
                margin-bottom: 0;
            }
        }
    }

    .dropdown-sm-square.show {
        .dropdown-toggle {
            background: $secondary;
            color: white;
        }
    }
    .lit-color-palette {
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
    &_raw {
        border: none;
        outline: none !important;
        box-shadow: none !important;
    }
}
</style>
