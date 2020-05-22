<template>
    <fj-form-item :field="field" :model="model" :value="fileCount">
        <template v-if="model.id">
            <div class="w-100">
                <fj-field-alert-empty
                    v-if="images.length == 0"
                    :field="field"
                    :class="{ 'mb-0': field.readonly }"
                />
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
                                @vdropzone-error="uploadError"
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
                dictDefaultMessage: `<i class="fas fa-file-import d-block"></i> ${this.__(
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
        getCropperId() {
            return `fj-cropper-${this.field.route_prefix.replace(/\//g, '-')}`;
        },
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
            this.$emit('reload');
            Fjord.bus.$emit('field:updated', 'image:uploaded');
        },
        queueComplete() {
            this.busy = false;
        },
        uploadError(file, errorMessage, xhr) {
            this.$bvToast.toast(errorMessage.message, {
                variant: 'danger'
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
            if (this.field.crop === false) {
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
                this.$bvModal.hide(`${this.getCropperId()}`);
            });

            this.resetCropper();
        },
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

div#fjord-app .fj-dropzone {
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
