<template>
    <fj-form-item :field="field" :model="model">
        <codemirror
            :class="`fj-code-${field.id}`"
            :value="value"
            :options="options"
            v-bind:readonly="field.readonly"
            theme="default"
            @input="changed"
            @blur="blur"
            @focus="focus"
        />
    </fj-form-item>
</template>

<script>
import methods from '../methods';
import 'codemirror/lib/codemirror.css';

const path = require('path');

function requireAll(requireContext) {
    return requireContext.keys().reduce((previous, current) => {
        const name = current.substring(
            current.lastIndexOf('/') + 1,
            current.lastIndexOf('.')
        );
        previous[name] = requireContext(current);
        return previous;
    }, {});
}

const actionCreators = requireAll(
    require.context('codemirror/mode', true, /^\.\/.*\.js$/)
);

let modes = {};

export default {
    name: 'FieldCode',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            value: null
        };
    },
    beforeMount() {
        this.init();
        this.options.readOnly = this.readonly;
    },
    methods: {
        ...methods,
        changed(value) {
            this.setValue(value);
            this.$emit('changed', value);
        },
        focus(cm) {
            $(`.fj-code-${this.field.id}`).addClass('focus');
        },
        blur(cm) {
            $(`.fj-code-${this.field.id}`).removeClass('focus');
        }
    },
    data() {
        return {
            value: null,
            options: {
                tabSize: this.field.tab_size,
                mode: this.field.language,
                theme: this.field.theme,
                lineNumbers: this.field.line_numbers,
                line: this.field.line,
                readOnly: false,
                tabSize: 4
            }
        };
    }
};
</script>

<style lang="scss">
@import '~codemirror/theme/3024-day.css';
@import '~codemirror/theme/3024-night.css';
@import '~codemirror/theme/abcdef.css';
@import '~codemirror/theme/ambiance-mobile.css';
@import '~codemirror/theme/ambiance.css';
@import '~codemirror/theme/base16-dark.css';
@import '~codemirror/theme/base16-light.css';
@import '~codemirror/theme/bespin.css';
@import '~codemirror/theme/blackboard.css';
@import '~codemirror/theme/cobalt.css';
@import '~codemirror/theme/colorforth.css';
@import '~codemirror/theme/darcula.css';
@import '~codemirror/theme/dracula.css';
@import '~codemirror/theme/duotone-dark.css';
@import '~codemirror/theme/duotone-light.css';
@import '~codemirror/theme/eclipse.css';
@import '~codemirror/theme/elegant.css';
@import '~codemirror/theme/erlang-dark.css';
@import '~codemirror/theme/gruvbox-dark.css';
@import '~codemirror/theme/hopscotch.css';
@import '~codemirror/theme/icecoder.css';
@import '~codemirror/theme/idea.css';
@import '~codemirror/theme/isotope.css';
@import '~codemirror/theme/lesser-dark.css';
@import '~codemirror/theme/liquibyte.css';
@import '~codemirror/theme/lucario.css';
@import '~codemirror/theme/material-darker.css';
@import '~codemirror/theme/material-ocean.css';
@import '~codemirror/theme/material-palenight.css';
@import '~codemirror/theme/material.css';
@import '~codemirror/theme/mbo.css';
@import '~codemirror/theme/mdn-like.css';
@import '~codemirror/theme/midnight.css';
@import '~codemirror/theme/monokai.css';
@import '~codemirror/theme/moxer.css';
@import '~codemirror/theme/neat.css';
@import '~codemirror/theme/neo.css';
@import '~codemirror/theme/night.css';
@import '~codemirror/theme/nord.css';
@import '~codemirror/theme/oceanic-next.css';
@import '~codemirror/theme/panda-syntax.css';
@import '~codemirror/theme/paraiso-dark.css';
@import '~codemirror/theme/paraiso-light.css';
@import '~codemirror/theme/pastel-on-dark.css';
@import '~codemirror/theme/railscasts.css';
@import '~codemirror/theme/rubyblue.css';
@import '~codemirror/theme/seti.css';
@import '~codemirror/theme/shadowfox.css';
@import '~codemirror/theme/solarized.css';
@import '~codemirror/theme/ssms.css';
@import '~codemirror/theme/the-matrix.css';
@import '~codemirror/theme/tomorrow-night-bright.css';
@import '~codemirror/theme/tomorrow-night-eighties.css';
@import '~codemirror/theme/ttcn.css';
@import '~codemirror/theme/twilight.css';
@import '~codemirror/theme/vibrant-ink.css';
@import '~codemirror/theme/xq-dark.css';
@import '~codemirror/theme/xq-light.css';
@import '~codemirror/theme/yeti.css';
@import '~codemirror/theme/yonce.css';
@import '~codemirror/theme/zenburn.css';

@import '@fj-sass/_variables';

.vue-codemirror {
    width: 100%;
    //font-size: 1rem;

    @include border-radius($input-border-radius, 0);

    &.focus {
        color: $input-focus-color;
        background-color: $input-focus-bg;
        border-color: $input-focus-border-color;
        outline: 0;
        // Avoid using mixin so we can pass custom focus shadow properly
        @if $enable-shadows {
            box-shadow: $input-box-shadow, $input-focus-box-shadow;
        } @else {
            box-shadow: $input-focus-box-shadow;
        }
    }

    .CodeMirror {
        @include border-radius($input-border-radius, 0);
        height: auto;

        @include font-size($input-font-size);
        border: $input-border-width solid $input-border-color;
        font-weight: $input-font-weight;

        .CodeMirror-gutters {
            padding: $input-padding-y 0;
        }
        .CodeMirror-lines {
            padding: $input-padding-y 0;
        }
    }

    &[readonly] {
        .CodeMirror {
            background-color: $input-disabled-bg;
        }
    }
}
</style>
