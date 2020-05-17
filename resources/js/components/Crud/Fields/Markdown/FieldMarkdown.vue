<template>
    <b-col :cols="field.cols">
        <fj-markdown :markdown="markdown" />
    </b-col>
</template>

<script>
export default {
    name: 'FieldMarkdown',
    props: {
        field: {
            type: Object,
            required: true
        },
        markdown: {
            type: String,
            required: true
        }
    }
};
</script>
