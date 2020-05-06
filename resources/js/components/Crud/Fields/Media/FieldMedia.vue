<template>
    <fj-form-item :field="field" :model="model" :value="fileCount">
        <template v-if="model.id">
            <div class="w-100">
                <b-row>
                    <div
                        class="col-12 order-2"
                        v-if="!field.readonly && images.length == 0"
                    >
                        <vue-dropzone
                            class="fj-dropzone"
                            :ref="`dropzone-${field.id}`"
                            :id="`dropzone-${field.id}`"
                            :options="dropzoneOptions"
                            @vdropzone-success="uploadSuccess"
                            @vdropzone-error="uploadError"
                            @vdropzone-files-added="processQueue"
                        />
                    </div>
                    <div class="col-12 order-1">
                        <fj-field-media-images
                            :field="field"
                            :images="images"
                            :model="model"
                            :model-id="modelId"
                            :readonly="field.readonly"
                            v-if="images.length > 0"
                        >
                            <vue-dropzone
                                v-if="
                                    !field.readonly &&
                                        images.length > 0 &&
                                        images.length < field.maxFiles
                                "
                                slot="drop"
                                class="fj-dropzone"
                                :ref="`dropzone-${field.id}`"
                                :id="`dropzone-${field.id}`"
                                :options="dropzoneOptions"
                                @vdropzone-success="uploadSuccess"
                                @vdropzone-error="uploadError"
                                @vdropzone-files-added="processQueue"
                            />
                        </fj-field-media-images>
                        <fj-field-alert-empty
                            v-else
                            :field="field"
                            :class="{ 'mb-0': field.readonly }"
                        />
                    </div>
                </b-row>
            </div>
            <b-modal
                :id="`fj-cropper-${field.id}`"
                size="xl"
                title="Crop Image"
                :static="true"
            >
                <div class="row">
                    <div class="col-8">
                        <div class="card no-fx">
                            <div class="fj-cropper__canvas-wrapper r4x3">
                                <div class="fj-cropper__canvas"></div>
                            </div>
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
    </fj-form-item>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone';
import { mapGetters } from 'vuex';

export default {
    name: 'FieldMedia',
    props: {
        model: {
            required: true,
            type: Object
        },
        field: {
            required: true,
            type: Object
        },
        modelId: {
            type: [Boolean, Number]
        }
    },
    components: {
        vueDropzone: vue2Dropzone
    },
    data() {
        let self = this;
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
                acceptedFiles: 'image/*, application/pdf',
                dictDefaultMessage: `<i class="fas fa-file-import mr-2"></i> ${this.__(
                    'fj.drag_and_drop'
                )}`,
                headers: {
                    'X-CSRF-TOKEN': document.head.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                params: {
                    collection: this.field.id
                }
            }
        };
    },
    beforeMount() {
        this.media = this.model[this.field.id] || [];

        this.dropzoneOptions.url = this.getUploadUrl();

        this.images = this.media;
        // TODO: FIX FOR BLOCK
        if (Object.keys(this.media)[0] != '0' && !_.isEmpty(this.media)) {
            this.images = [this.media];
        }
    },
    computed: {
        ...mapGetters(['baseURL', 'language']),
        dropzone() {
            return this.$refs[`dropzone-${this.field.id}`];
        },
        maxFiles() {
            return this.images.length + this.uploads >= this.field.maxFiles;
        },
        fileCount() {
            let c = [];
            for (var i = 0; i < this.images.length; i++) {
                c.push(1);
            }
            return c;
        }
    },
    methods: {
        getUploadUrl() {
            return `${this.baseURL}${this.field.route_prefix}/media`;
        },
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
        checkMaxFiles() {
            if (this.images.length >= this.field.maxFiles) {
                this.dropzone.removeAllFiles();
                this.dropzone.disable();
                $(`#dropzone-${this.field.id}`)
                    .addClass('disabled')
                    .find('.dz-message')
                    .html(
                        '<i class="far fa-images"></i> maximale Anzahl an Bildern erreicht'
                    );
                return true;
            }

            return false;
        },
        updateAttributes() {
            this.$emit('updateAttributes', this.images);
        },
        uploadSuccess(file, response) {
            this.uploads++;
            this.$bvToast.toast(this.__('fj.image_uploaded'), {
                variant: 'success'
            });
            this.images.push(response);
            Fjord.bus.$emit('field:updated', 'image:uploaded');
        },
        uploadError(file, errorMessage, xhr) {
            this.$bvToast.toast(errorMessage.message, {
                variant: 'danger',
                noAutoHide: true
            });
        },
        processQueue() {
            this.$nextTick(() => {
                this.dropzone.processQueue();
            });
        },
        transformFile(file, done) {
            // If image doesn't require cropping, return bare image
            //
            //
            if (!this.field.crop) {
                done(file);
                return;
            }

            // Set some constants
            //
            //
            const DROPZONE = this.dropzone;
            const CANVAS = $(
                `#fj-cropper-${this.field.id} .fj-cropper__canvas`
            );
            let uploadable = true;

            // Show the cropping modal
            //
            //
            this.$bvModal.show(`fj-cropper-${this.field.id}`);

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
                aspectRatio: this.field.ratio,
                viewMode: 2,
                preview: $(
                    `#fj-cropper-${this.field.id} .fj-cropper__preview`
                )[0]
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
                this.$bvModal.hide(`fj-cropper-${this.field.id}`);
            });

            $('body').on('click', '#cropper-save', () => {
                if (uploadable) {
                    // Get the canvas with image data from Cropper.js
                    var canvas = cropper.getCroppedCanvas({
                        width: 1400,
                        height: 1000
                    });
                    // Turn the canvas into a Blob (file object without a name)
                    canvas.toBlob(function(blob) {
                        done(blob);
                    });
                }

                CANVAS.html('');
                this.$bvModal.hide(`fj-cropper-${this.field.id}`);
            });
        }
    }
};
</script>
<style lang="scss">
@import '@fj-sass/_variables';

.dz-error-message {
    display: none !important;
}

div#fjord-app .fj-dropzone {
    border: $input-border-width solid $input-border-color;
    border-radius: $input-border-radius;
    background-color: $input-bg;
    color: $input-color;
    font-size: $input-font-size;

    &:hover {
        background-color: $light;
    }

    i {
        color: $nav-item-icon-color;
    }
}

.fj-cropper {
    position: fixed;
    //display: none;
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
            height: 0px;
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
</style>
