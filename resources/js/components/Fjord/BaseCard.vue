<template>
    <b-col :cols="cols">
        <b-card :title="title">
            <b-row>
                <component
                    v-for="(c, key) in component"
                    :key="key"
                    :is="c.name"
                    v-bind="{ ...c.props, ...$attrs }"
                />
            </b-row>
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
            type: String
        },
        component: {
            type: Array,
            required: true
        }
    }
};
</script>
