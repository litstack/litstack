<template>
    <img
        :src="src"
        :style="`max-width: ${maxWidth}; max-height: ${maxHeight};`"
    />
</template>

<script>
export default {
    name: 'ColImage',
    props: {
        src: {
            type: String,
            required: true
        },
        maxWidth: {
            type: String,
            default() {
                return '70px';
            }
        },
        maxHeight: {
            type: String,
            default() {
                return '50px';
            }
        }
    }
};
</script>
