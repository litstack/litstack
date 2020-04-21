<template>
    <b-col :cols="cols">
        <b-card :title="title">
            <component
                :is="component.name"
                v-bind="{ ...component.props, ...$attrs }"
            />
        </b-card>
    </b-col>
</template>

<script>
export default {
    name: 'Card',
    props: {
        cols: {
            type: Number,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        component: {
            type: Object,
            required: true
        }
    }
};
</script>
