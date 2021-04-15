<template>
    <lit-base-field :field="field" :model="model" :value="fileCount">
        <template v-if="model.id">
            <div class="w-100">
                <b-row>
                    <div class="col-12 order-1">
                        <lit-field-media-images
                            :field="field"
                            :images="images"
                            :model="model"
                            :model-id="modelId"
                            :readonly="field.readonly"
                            @deleted="$emit('reload')"
                            @newOrder="$emit('reload')"
                        >
                            <div
                                class="lit-dropzone-busy"
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
                                class="lit-dropzone"
                                :ref="`dropzone-${field.id}`"
                                :id="`dropzone-${field.id}`"
                                :options="dropzoneOptions"
                                @vdropzone-sending="busy = true"
                                @vdropzone-success="uploadSuccess"
                                @vdropzone-queue-complete="queueComplete"
                                @vdropzone-error="handleUploadError"
                                @vdropzone-files-added="processQueue"
                            />
                        </lit-field-media-images>
                    </div>
                </b-row>
            </div>
            <b-modal
                :id="cropperId"
                size="full"
                v-if="field.crop !== false"
                @shown="crop()"
                @close="cancel()"
            >
                <div class="row full-height">
                    <div class="col-8 full-height">
                        <div class="lit-cropper__canvas-wrapper">
                            <div
                                class="lit-cropper__canvas full-height"
                                ref="cropperCanvas"
                            ></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="r4x3">
                            <div
                                class="lit-cropper__preview"
                                ref="cropperPreview"
                            ></div>
                        </div>
                        <div class="pt-4" v-if="cropperSettings">
                            <table class="table table-sm">
                                <tr>
                                    <td>Width:</td>
                                    <td>
                                        {{ Math.round(cropperSettings.width) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Height:</td>
                                    <td>
                                        {{ Math.round(cropperSettings.height) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>X:</td>
                                    <td>{{ Math.round(cropperSettings.x) }}</td>
                                </tr>
                                <tr>
                                    <td>X:</td>
                                    <td>{{ Math.round(cropperSettings.y) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div
                    slot="modal-footer"
                    class="w-100 d-flex justify-content-end"
                >
                    <b-button variant="secondary" size="sm" @click="cancel()">
                        Cancel
                    </b-button>
                    <b-button
                        @click="save()"
                        variant="primary"
                        size="sm"
                        class="ml-2"
                    >
                        Save
                        <b-spinner
                            variant="secondary"
                            v-if="busy"
                            small
                        ></b-spinner>
                    </b-button>
                </div>
            </b-modal>
        </template>
        <template v-else>
            <lit-field-alert-not-created :field="field" class="mb-0" />
        </template>
    </lit-base-field>
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
            type: Object,
        },

        /**
         * Field attributes.
         */
        field: {
            required: true,
            type: Object,
        },

        /**
         * Model id.
         */
        modelId: {
            type: [Boolean, Number],
        },
    },
    components: {
        vueDropzone: vue2Dropzone,
    },
    data() {
        let self = this;

        let params = {
            collection: this.field.id,
            field_id: this.field.id,
            ...(this.field.params || {}),
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
                autoProcessQueue: true,
                thumbnailWidth: 150,
                maxFilesize: 100,
                maxFiles: this.field.maxFiles,
                method: 'POST',
                paramName: 'media',
                dictDefaultMessage: `<i class="fas fa-file-import d-block"></i> ${this.__(
                    'base.drag_and_drop'
                )}`,
                headers: {
                    'X-CSRF-TOKEN': document.head.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                params,
            },
            busy: false,
            uploadProgress: 0,
            // Cropper
            cropper: null,
            image: null,
            canvas: null,
            file: null,
            done: null,
            cropperSettings: null,
        };
    },
    beforeMount() {
        this.media = this.model[this.field.id] || [];

        this.dropzoneOptions.url = this.uploadUrl;

        if (this.field.accept !== true && this.field.accept !== undefined) {
            this.dropzoneOptions.acceptedFiles = this.field.accept;
        }

        this.images = this.media;
        // TODO: FIX FOR BLOCK
        if (Object.keys(this.media)[0] != '0' && !_.isEmpty(this.media)) {
            this.images = [this.media];
        }

        document.addEventListener('keyup', evt => {
            if (evt.keyCode === 27) {
                this.cancel();
            }
        });
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
        },

        /**
         * Cropper id.
         */
        cropperId() {
            return `lit-cropper-${this.field.route_prefix.replace(
                /\//g,
                '-'
            )}-${this.model.id}-${this.field.id}`;
        },

        /**
         * The upload url.
         */
        uploadUrl() {
            return `${this.baseURL}${this.field.route_prefix}/media`;
        },
    },
    watch: {
        images(val) {
            if (val.length == this.field.maxFiles) {
                // this is done, because the event can't be fired off the
                // component, when max files are reached
                this.queueComplete();
            }
        },
    },
    methods: {
        /**
         * Cancel cropping.
         */
        cancel() {
            this.dropzone.removeAllFiles();
            this.dropzone.removeAllFiles(true);
            this.$bvModal.hide(this.cropperId);
        },
        /**
         * Upload file with cropping information
         */
        save() {
            let done = this.done;
            this.dropzoneOptions.params.crop = JSON.stringify(
                this.cropperSettings
            );
            this.done(this.file);
        },

        /**
         * Initialize cropper.
         */
        initCropper() {
            this.cropper = null;
            this.image = null;
            this.canvas = null;
            this.file = null;
            this.done = null;
            this.cropperSettings = null;
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
                    title: '',
                };
            }

            return image.custom_properties[this.language][key];
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
            this.busy = false;
            this.uploads++;
            this.$bvToast.toast(
                this.__('crud.fields.media.messages.image_uploaded'),
                {
                    variant: 'success',
                }
            );
            this.images.push(response);
            this.$bvModal.hide(this.cropperId);
        },

        /**
         * Handle queue complete.
         */
        queueComplete() {
            this.busy = false;
            this.$emit('reload');
            Lit.bus.$emit('field:updated', 'image:uploaded');
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
                variant: 'danger',
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
            this.initCropper();
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

            this.$bvModal.show(this.cropperId);
        },

        /**
         * Crop image.
         */
        crop() {
            // Set some constants
            //
            //
            this.canvas = this.$refs.cropperCanvas;

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
            });

            this.image.addEventListener('crop', event => {
                this.cropperSettings = event.detail;
            });
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
