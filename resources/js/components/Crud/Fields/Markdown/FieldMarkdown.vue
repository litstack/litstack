<template>
    <lit-col :width="width">
        <lit-markdown :markdown="markdown" />
    </lit-col>
</template>

<script>
export default {
    name: 'FieldMarkdown',
    props: {
        width: {
            type: [String, Number],
            default() {
                return '12';
            },
        },
        markdown: {
            type: String,
            required: true,
        },
    },
};
</script>
