<template>
    <fj-col :width="width">
        <b-card :title="title">
            <b-row>
                <slot />
            </b-row>
        </b-card>
    </fj-col>
</template>

<script>
export default {
    name: 'FieldWrapperCard',
    props: {
        title: {
            type: String
        },
        width: {
            type: Number,
            default() {
                return 12;
            }
        }
    }
};
</script>
