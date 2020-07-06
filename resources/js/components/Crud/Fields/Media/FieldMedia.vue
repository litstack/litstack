<template>
    <fj-base-field :field="field" :model="model" :value="fileCount">
        <template v-if="model.id">
            <div class="w-100">
                <!--
                <fj-field-alert-empty
                    v-if="images.length == 0"
                    :field="field"
                    :class="{ 'mb-0': field.readonly }"
                />
                -->
                <b-row>
                    <div class="col-12 order-1">
                        <fj-field-media-images
                            :field="field"
                            :images="images"
                            :model="model"
                            :model-id="modelId"
                            :readonly="field.readonly"
                            @deleted="$emit('reload')"
                            @newOrder="$emit('reload')"
                        >
                            <div
                                class="fj-dropzone-busy"
                                v-if="busy && images.length < field.maxFiles"
                                slot="busy"
                            >
                                <b-spinner variant="secondary"></b-spinner>
                            </div>

                            <vue-dropzone
                                v-if="
                                    !field.readonly &&
                                        images.length < field.maxFiles
                                "
                                slot="drop"
                                class="fj-dropzone"
                                :ref="`dropzone-${field.id}`"
                                :id="`dropzone-${field.id}`"
                                :options="dropzoneOptions"
                                @vdropzone-sending="busy = true"
                                @vdropzone-success="uploadSuccess"
                                @vdropzone-queue-complete="queueComplete"
                                @vdropzone-error="handleUploadError"
                                @vdropzone-files-added="processQueue"
                            />
                        </fj-field-media-images>
                    </div>
                </b-row>
            </div>
            <b-modal
                :id="getCropperId()"
                size="full"
                v-if="field.crop !== false"
                @shown="crop()"
                @hidden="resetCropper()"
            >
                <div class="row full-height">
                    <div class="col-8 full-height">
                        <div class="fj-cropper__canvas-wrapper">
                            <div class="fj-cropper__canvas full-height"></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="r4x3">
                            <div class="fj-cropper__preview"></div>
                        </div>
                    </div>
                </div>
                <div
                    slot="modal-footer"
                    class="w-100 d-flex justify-content-end"
                >
                    <b-button id="cropper-cancel" variant="secondary" size="sm">
                        Cancel
                    </b-button>
                    <b-button
                        id="cropper-save"
                        variant="primary"
                        size="sm"
                        class="ml-2"
                    >
                        Save
                    </b-button>
                </div>
            </b-modal>
        </template>
        <template v-else>
            <fj-field-alert-not-created :field="field" class="mb-0" />
        </template>
    </fj-base-field>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone';
import { mapGetters } from 'vuex';

export default {
    name: 'FieldMedia',
    props: {
        /**
         * Model.
         */
        model: {
            required: true,
            type: Object
        },

        /**
         * Field attributes.
         */
        field: {
            required: true,
            type: Object
        },

        /**
         * Model id.
         */
        modelId: {
            type: [Boolean, Number]
        }
    },
    components: {
        vueDropzone: vue2Dropzone
    },
    data() {
        let self = this;

        let params = {
            collection: this.field.id,
            field_id: this.field.id,
            ...(this.field.params || {})
        };

        if (params.field_id != this.field.id) {
            params.child_field_id = this.field.id;
        }

        return {
            media: [],
            images: [],
            uploads: 0,
            dropzoneOptions: {
                url: '',
                transformFile: this.transformFile,
                parallelUploads: 1,
                autoProcessQueue: false,
                thumbnailWidth: 150,
                maxFilesize: 100,
                maxFiles: this.field.maxFiles,
                method: 'POST',
                paramName: 'media',
                dictDefaultMessage: `<i class="fas fa-file-import d-block"></i> ${this.__(
                    'fj.drag_and_drop'
                )}`,
                headers: {
                    'X-CSRF-TOKEN': document.head.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                params
            },
            busy: false,
            uploadProgress: 0,
            file: null,
            done: null
        };
    },
    beforeMount() {
        this.media = this.model[this.field.id] || [];

        this.dropzoneOptions.url = this.getUploadUrl();

        if (this.field.accept !== true && this.field.accept !== undefined) {
            this.dropzoneOptions.acceptedFiles = this.field.accept;
        }

        this.images = this.media;
        // TODO: FIX FOR BLOCK
        if (Object.keys(this.media)[0] != '0' && !_.isEmpty(this.media)) {
            this.images = [this.media];
        }
    },
    computed: {
        ...mapGetters(['baseURL', 'language']),

        /**
         * Dropzone ref.
         */
        dropzone() {
            return this.$refs[`dropzone-${this.field.id}`];
        },

        /**
         * Determines if max files are reached.
         */
        maxFiles() {
            return this.images.length + this.uploads >= this.field.maxFiles;
        },

        /**
         * Files count array.
         */
        fileCount() {
            let count = [];
            for (var i = 0; i < this.images.length; i++) {
                count.push(1);
            }
            return count;
        }
    },
    watch: {
        images(val) {
            if (val.length == this.field.maxFiles) {
                // this is done, because the event can't be fired off the
                // component, when max files are reached
                this.queueComplete();
            }
        }
    },
    methods: {
        /**
         * Get cropper id.
         */
        getCropperId() {
            return `fj-cropper-${this.field.route_prefix.replace(/\//g, '-')}`;
        },

        /**
         * Get upload url.
         */
        getUploadUrl() {
            return `${this.baseURL}${this.field.route_prefix}/media`;
        },

        /**
         * Get custom property by image and property name.
         */
        getCustomProperty(image, key) {
            if (!this.field.translatable) {
                return image.custom_properties[key];
            }

            if (!(this.language in image.custom_properties)) {
                image.custom_properties[this.language] = {
                    alt: '',
                    title: ''
                };
            }

            return image.custom_properties[this.language][key];
        },

        /**
         * Check if maxfiles are reached.
         */
        checkMaxFiles() {
            if (this.maxFilesReached()) {
                this.dropzone.removeAllFiles();
                this.dropzone.disable();
                $(`#dropzone-${this.field.id}`)
                    .addClass('disabled')
                    .find('.dz-message')
                    .html(
                        '<i class="far fa-images"></i> maximale Anzahl an Bildern erreicht'
                    );
            }

            return this.maxFilesReached();
        },

        /**
         * Determine wheter max files are reached.
         */
        maxFilesReached() {
            return this.images.length >= this.field.maxFiles;
        },

        /**
         * Update attributes.
         */
        updateAttributes() {
            this.$emit('updateAttributes', this.images);
        },

        /**
         * Handle upload success.
         */
        uploadSuccess(file, response) {
            this.uploads++;
            this.$bvToast.toast(this.__('fj.image_uploaded'), {
                variant: 'success'
            });
            this.images.push(response);
            this.$emit('reload');
            Fjord.bus.$emit('field:updated', 'image:uploaded');
        },

        /**
         * Handle queue complete.
         */
        queueComplete() {
            this.busy = false;
        },

        /**
         * Handle upload error.
         */
        handleUploadError(file, errorMessage, xhr) {
            let message = errorMessage;

            this.dropzone.removeAllFiles();
            if (typeof errorMessage == 'object') {
                if ('errors' in errorMessage) {
                    if ('media' in errorMessage.errors) {
                        for (let i in errorMessage.errors.media) {
                            message = errorMessage.errors.media[i];
                        }
                        return;
                    }
                } else if ('message' in errorMessage) {
                    message = errorMessage.message;
                }
            }

            this.$bvToast.toast(message, {
                variant: 'danger'
            });
        },

        /**
         * Handle process queue.
         */
        processQueue() {
            this.$nextTick(() => {
                this.dropzone.processQueue();
            });
        },

        /**
         * Transform file before upload.
         */
        transformFile(file, done) {
            // If image doesn't require cropping, return bare image
            //
            //
            if (this.field.crop === false || this.field.crop === undefined) {
                done(file);
                return;
            }

            // Show the cropping modal
            //
            //
            this.file = file;
            this.done = done;
            this.$bvModal.show(`${this.getCropperId()}`);
        },

        /**
         * Crop image.
         */
        crop() {
            let file = this.file;
            let done = this.done;

            // Set some constants
            //
            //
            const DROPZONE = this.dropzone;
            const CANVAS = $(`#${this.getCropperId()} .fj-cropper__canvas`);
            let uploadable = true;

            // Create an image node for Cropper.js
            //
            //
            var image = new Image();
            image.src = URL.createObjectURL(file);

            CANVAS.append(image);

            // Create Cropper
            //
            //
            var cropper = new Cropper(image, {
                aspectRatio: this.field.crop,
                viewMode: 2,
                preview: $(`#${this.getCropperId()} .fj-cropper__preview`)[0]
            });

            // User Actions
            //
            //
            $(document).keydown(e => {
                if (e.keyCode == 27) {
                    uploadable = false;
                    this.dropzone.removeAllFiles();
                    // Cancel current uploads
                    this.dropzone.removeAllFiles(true);
                    CANVAS.html('');
                }
            });

            $('body').on('click', '#cropper-cancel', () => {
                uploadable = false;
                this.dropzone.removeAllFiles();
                // Cancel current uploads
                this.dropzone.removeAllFiles(true);
                CANVAS.html('');
                this.$bvModal.hide(`${this.getCropperId()}`);
            });

            $('body').on('click', '#cropper-save', () => {
                if (uploadable) {
                    // Get the canvas with image data from Cropper.js
                    // set the canvas size to the actual pictures width and height
                    // to prevent the original size
                    var canvas = cropper.getCroppedCanvas({
                        width: file.width,
                        height: file.height
                    });
                    // Turn the canvas into a Blob (file object without a name)
                    canvas.toBlob(function(blob) {
                        done(blob);
                    });
                }

                CANVAS.html('');
                this.$bvModal.hide(`${this.getCropperId()}`);
            });

            this.resetCropper();
        },

        /**
         * Reset cropper.
         */
        resetCropper() {
            this.file = null;
            this.done = null;
        }
    }
};
</script>
<style lang="scss">
@import '@fj-sass/_variables';

.dz-error-message {
    display: none !important;
}
.dz-preview {
    display: none !important;
}

.fj-dropzone {
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

.fj-cropper {
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
