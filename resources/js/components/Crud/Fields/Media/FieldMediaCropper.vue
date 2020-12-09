<template>
    <div class="lit-cropper__canvas-wrapper">
        <div class="lit-cropper__canvas full-height" ref="cropperCanvas"></div>
    </div>
</template>

<script>
export default {
    name: 'FieldMediaCropper',
    props: {
        /**
         * Model.
         */
        file: {
            required: true,
            type: File,
        },

        /**
         * Field attributes.
         */
        field: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            // Cropper
            cropper: null,
            image: null,
            canvas: null,
            cropperSettings: null,
        };
    },
    beforeMount() {
        this.$on('crop', (data) => this.crop(data));
    },
    mounted() {
        //this.crop();
    },
    methods: {
        /**
         * Crop image.
         *
         * @param {Object} data
         */
        crop(data = {}) {
            this.reset();

            this.$nextTick(() => {
                this.init(data);
            });
        },

        init(data) {
            // Set some constants
            //
            //
            this.canvas = this.$refs.cropperCanvas;
            window.canvas = this.canvas;

            // Create an image node for Cropper.js
            //
            //
            this.image = new Image();
            this.image.src = URL.createObjectURL(this.file);

            this.canvas.append(this.image);

            // Create Cropper
            //
            //
            this.cropper = new Cropper(this.image, {
                aspectRatio: this.field.crop,
                viewMode: 2,
                preview: document.querySelector('.lit-cropper__preview'),
                data,
            });

            let applyCrop = Lit.debounce(
                (event) => this.$emit('cropped', event.detail),
                100
            );

            this.image.addEventListener('crop', (event) => {
                applyCrop(event);
            });
        },

        reset() {
            try {
                this.cropper.close();
            } catch (e) {}

            this.cropper = null;
            this.canvas = null;
            this.image = null;
        },
    },
};
</script>
<style lang="scss">
@import '@lit-sass/_variables';

.dz-error-message {
    display: none !important;
}
.dz-preview {
    display: none !important;
}

.lit-dropzone {
    min-height: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: $input-border-width solid $input-border-color;
    border-radius: $input-border-radius;
    background-color: $input-bg;
    color: $input-color;
    font-size: $input-font-size-sm;
    font-family: inherit;

    &:hover {
        background-color: $light;
    }

    i {
        color: $nav-item-icon-color;
    }

    &-busy {
        min-height: 50px;
        border: $input-border-width solid $input-border-color;
        border-radius: $input-border-radius;
        display: flex;
        justify-content: center;
        align-items: center;
    }
}

.lit-cropper {
    position: fixed;
    display: none;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.3);
    z-index: 10;

    &__modal {
        width: 80vw;
        height: 70vh;
        background-color: white;
        border-radius: 5px;
        margin: auto;
        margin-top: 10vh;
        padding: 20px;
    }
    &__canvas,
    &__preview {
        &-wrapper {
            height: 100%;
            position: relative;
        }
        position: absolute;
        overflow: hidden;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
}
.r2x1 {
    position: relative;
    padding-bottom: 50%;
}
.r4x3 {
    position: relative;
    padding-bottom: 75%;
}
.r16x9 {
    position: relative;
    padding-bottom: 56.25%;
}
.r16x10 {
    position: relative;
    padding-bottom: 62.5%;
}
</style>
