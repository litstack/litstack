<template>
    <img
        v-if="field.type == 'image'"
        :src="imgPath"
        :style="`object-fit: ${field.showFullImage ? 'contain' : 'cover'}`"
    />
</template>

<script>
export default {
    name: 'FieldMediaImage',
    props: {
        image: {
            type: Object,
        },
        field: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {};
    },
    computed: {
        /**
         * Image path.
         */
        imgPath() {
            if (this.image.mime_type == 'image/svg+xml') {
                return this.image.original_url;
            }
            if (this.image.showOrignial == true) {
                return this.image.original_url;
            }
            return this.image.url || this.image.original_url;
        },
    },
};
</script>
