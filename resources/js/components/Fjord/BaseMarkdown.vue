<template>
    <div class="fj-markdown" v-html="compiledMarkdown" />
</template>

<script>
import Prism from 'prismjs';
import marked from 'marked';
import { languages } from 'prismjs/components';
require(`prismjs/components/prism-markup-templating`);

export default {
    name: 'Markdown',
    props: {
        markdown: {
            type: String,
            required: true
        }
    },
    beforeMount() {
        this.loadLanguages();
    },
    methods: {
        loadLanguages() {
            for (let language in languages) {
                try {
                    require(`prismjs/components/prism-${language}`);
                } catch (e) {}
            }
        }
    },
    computed: {
        compiledMarkdown() {
            return marked(this.markdown, {
                sanitize: true,
                highlight: function(code, language) {
                    if (!language in Prism.languages) {
                        return code;
                    }

                    return Prism.highlight(
                        code,
                        Prism.languages[language],
                        language
                    );
                }
            });
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';
@import '~prismjs/themes/prism-tomorrow.css';

pre[class*='language-'] {
    border-radius: $border-radius;
    border: none;
    background: #282c34;
    padding: 1.5rem;
}
code[class*='language-'],
pre[class*='language-'] {
    font-size: $font-size-sm;
}
code:not([class*='language-']) {
    color: $primary;
    padding: 0.25rem 0.5rem;
    margin: 0;
    font-size: 0.85em;
    background-color: rgba(27, 31, 35, 0.05);
    border-radius: 3px;
}
</style>
