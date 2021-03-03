<template>
    <div class="lit-markdown" v-html="markdown" />
</template>

<script>
export default {
    name: 'Markdown',
    props: {
        markdown: {
            type: String,
            required: true,
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';

/**
 * PrismJS
 * https://prismjs.com/download.html#themes=prism&languages=markup+css+clike+javascript+markup-templating+php&plugins=line-numbers 
 *
 * prism.js default theme for JavaScript, CSS and HTML
 * Based on dabblet (http://dabblet.com)
 * @author Lea Verou
 */

code[class*='language-'],
pre[class*='language-'] {
    color: $purple;
    background: none;
    text-align: left;
    white-space: pre;
    word-spacing: normal;
    word-break: normal;
    word-wrap: normal;
    border-radius: 0.5rem;

    -moz-tab-size: 4;
    -o-tab-size: 4;
    tab-size: 4;

    -webkit-hyphens: none;
    -moz-hyphens: none;
    -ms-hyphens: none;
    hyphens: none;
}

pre[class*='language-']::-moz-selection,
pre[class*='language-'] ::-moz-selection,
code[class*='language-']::-moz-selection,
code[class*='language-'] ::-moz-selection {
    text-shadow: none;
    background: #b3d4fc;
}

pre[class*='language-']::selection,
pre[class*='language-'] ::selection,
code[class*='language-']::selection,
code[class*='language-'] ::selection {
    text-shadow: none;
    background: #b3d4fc;
}

@media print {
    code[class*='language-'],
    pre[class*='language-'] {
        text-shadow: none;
    }
}

/* Code blocks */
pre[class*='language-'] {
    padding: 1em;
    margin: 0.5em 0 2em;
    overflow: auto;
    -webkit-overflow-scrolling: touch;
    max-width: 100%;
}

:not(pre) > code[class*='language-'],
pre[class*='language-'] {
    background: #f5f8fb;
    box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.075);
    color: $primary;
}

.token.comment,
.token.prolog,
.token.doctype,
.token.cdata {
    color: #93939e;
}

.token.punctuation {
    color: $black;
}

.namespace {
    opacity: 0.7;
}

.token.property,
.token.tag,
.token.boolean,
.token.number,
.token.constant,
.token.symbol,
.token.deleted {
    color: #d44545;
}

.token.selector,
.token.attr-name,
.token.string,
.token.char,
.token.builtin,
.token.inserted {
    color: #169f0c;
}

.token.operator,
.token.entity,
.token.url,
.language-css .token.string,
.style .token.string {
    color: $black;
}

.token.atrule,
.token.attr-value,
.token.keyword {
    color: #055472;
}

.token.function,
.token.class-name {
    color: $primary;
}

.token.regex,
.token.important,
.token.variable {
    color: #588bbd;
}

.token.important,
.token.bold {
    font-weight: bold;
}
.token.italic {
    font-style: italic;
}

.token.entity {
    cursor: help;
}

pre[class*='language-'].line-numbers {
    position: relative;
    padding-left: 3.8em;
    counter-reset: linenumber;
}

pre[class*='language-'].line-numbers > code {
    position: relative;
    white-space: inherit;
}

.line-numbers .line-numbers-rows {
    position: absolute;
    pointer-events: none;
    top: 0;
    font-size: 100%;
    left: -3.8em;
    width: 3em; /* works for line-numbers below 1000 lines */
    letter-spacing: -1px;

    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.line-numbers-rows > span {
    pointer-events: none;
    display: block;
    counter-increment: linenumber;
}

.line-numbers-rows > span:before {
    content: counter(linenumber);
    color: $black;
    display: block;
    padding-right: 0.8em;
    text-align: right;
}
</style>
