<template>
    <fj-col :cols="cols">
        <fj-markdown :markdown="markdown" />
    </fj-col>
</template>

<script>
export default {
    name: 'FieldMarkdown',
    props: {
        cols: {
            type: [String, Number],
            default() {
                return '12';
            }
        },
        markdown: {
            type: String,
            required: true
        }
    }
};
</script>
