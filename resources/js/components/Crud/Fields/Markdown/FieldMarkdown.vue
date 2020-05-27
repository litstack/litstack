<template>
    <fj-col :width="width">
        <fj-markdown :markdown="markdown" />
    </fj-col>
</template>

<script>
export default {
    name: 'FieldMarkdown',
    props: {
        width: {
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
