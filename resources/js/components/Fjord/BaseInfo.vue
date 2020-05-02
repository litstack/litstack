<template>
    <b-col :cols="cols">
        <component :is="heading" v-html="title" />
        <p v-for="info in text" v-html="info" class="text-secondary"></p>
    </b-col>
</template>

<script>
export default {
    name: 'Info',
    props: {
        cols: {
            type: Number,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        text: {
            type: Array,
            required: true
        },
        heading: {
            type: String,
            required: true
        },
        model: {},
        config: {}
    }
};
</script>
