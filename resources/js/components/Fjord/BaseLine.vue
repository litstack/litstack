<template>
    <b-col :cols="cols">
        <hr />
    </b-col>
</template>

<script>
export default {
    name: 'Line',
    props: {
        cols: {
            type: Number,
            default() {
                return 12;
            }
        }
    }
};
</script>
