<template>
    <fj-form-item :field="field" :model="model" :value="images">
        <template v-if="model.id">
            <div class="w-100">
                <b-row>
                    <div class="col-12 order-2" v-if="!maxFiles">
                        <vue-dropzone
                            class="fjord-dropzone"
                            :ref="`dropzone-${field.id}`"
                            :id="`dropzone-${field.id}`"
                            :options="dropzoneOptions"
                            @vdropzone-success="uploadSuccess"
                            @vdropzone-error="uploadError"
                            @vdropzone-files-added="processQueue"
                            @vdropzone-complete="removeFile"
                        ></vue-dropzone>
                    </div>
                    <div class="col-12 order-1">
                        <draggable v-model="images" class="row" @end="newOrder">
                            <div
                                :class="imgCols(field.image_size)"
                                v-for="(image, index) in images"
                                v-if="images.length > 0"
                                :key="image.id"
                            >
                                <div class="card no-fx mb-3 fjord-card">
                                    <div
                                        :class="{
                                            'fjord-card__1x1': field.square
                                        }"
                                        class="fjord-card__image"
                                    >
                                        <img :src="imgPath(image)" class="" />
                                    </div>
                                    <div class="text-right">
                                        <b-button
                                            size="sm"
                                            variant="link"
                                            class="text-secondary"
                                            v-b-modal="
                                                `fjord-image-${field.id}-${
                                                    image.id
                                                }`
                                            "
                                        >
                                            <i class="fas fa-edit"></i>
                                        </b-button>
                                    </div>
                                    <b-modal
                                        :id="
                                            `fjord-image-${field.id}-${
                                                image.id
                                            }`
                                        "
                                        size="xl"
                                        class="fjord-image-modal"
                                        :title="`${image.name}`"
                                        :static="true"
                                    >
                                        <div class="row">
                                            <div class="col-7">
                                                <img
                                                    :src="imgPath(image)"
                                                    class="img-fluid"
                                                />
                                            </div>
                                            <div class="col-5">
                                                <div class="mb-2">
                                                    <label class="mb-1">
                                                        Title
                                                    </label>
                                                    <b-badge
                                                        v-if="
                                                            field.translatable
                                                        "
                                                        variant="primary"
                                                    >
                                                        <small>{{ lng }}</small>
                                                    </b-badge>

                                                    <b-input
                                                        :size="'sm'"
                                                        :value="
                                                            getCustomProperty(
                                                                image,
                                                                'title'
                                                            )
                                                        "
                                                        @input="
                                                            changed(
                                                                $event,
                                                                'title',
                                                                image
                                                            )
                                                        "
                                                    />
                                                </div>
                                                <div>
                                                    <label class="mb-1"
                                                        >Alt</label
                                                    >
                                                    <b-badge
                                                        v-if="
                                                            field.translatable
                                                        "
                                                        variant="primary"
                                                    >
                                                        <small>{{ lng }}</small>
                                                    </b-badge>
                                                    <b-input
                                                        :size="'sm'"
                                                        :value="
                                                            getCustomProperty(
                                                                image,
                                                                'alt'
                                                            )
                                                        "
                                                        @input="
                                                            changed(
                                                                $event,
                                                                'alt',
                                                                image
                                                            )
                                                        "
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            slot="modal-footer"
                                            class="w-100 d-flex justify-content-between"
                                        >
                                            <button
                                                @click.prevent="
                                                    destroy(image.id, index)
                                                "
                                                class="btn btn-danger btn-sm"
                                            >
                                                <i class="far fa-trash-alt"></i>
                                                delete
                                            </button>
                                            <button
                                                @click.prevent="
                                                    $bvModal.hide(
                                                        `fjord-image-${
                                                            field.id
                                                        }-${image.id}`
                                                    )
                                                "
                                                class="btn btn-secondary btn-sm"
                                            >
                                                close
                                            </button>
                                        </div>
                                    </b-modal>
                                </div>
                            </div>
                        </draggable>
                    </div>
                </b-row>
            </div>
            <b-modal
                :id="`fjord-cropper-${field.id}`"
                size="xl"
                title="Crop Image"
                :static="true"
            >
                <div class="row">
                    <div class="col-8">
                        <div class="card no-fx">
                            <div class="fjord-cropper__canvas-wrapper r4x3">
                                <div class="fjord-cropper__canvas"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="r4x3">
                            <div class="fjord-cropper__preview"></div>
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
            <b-alert show variant="warning"
                >{{ form.config.names.title.singular }} has to be created in
                order to add <i>{{ field.title }}</i></b-alert
            >
        </template>
    </fj-form-item>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone';
import { mapGetters } from 'vuex';

export default {
    name: 'FormMedia',
    props: {
        model: {
            required: true,
            type: Object
        },
        field: {
            required: true,
            type: Object
        },
        id: {
            type: [Boolean, Number]
        },
        media: {
            type: [Object, Array],
            default: function() {
                return [];
            }
        }
    },
    components: {
        vueDropzone: vue2Dropzone
    },
    data() {
        let self = this;
        return {
            images: [],
            dropzoneOptions: {
                url: `${this.baseURL}media`,
                transformFile: this.transformFile,
                parallelUploads: 1,
                autoProcessQueue: false,
                thumbnailWidth: 150,
                maxFilesize: 100,
                maxFiles: this.field.maxFiles,
                method: 'POST',
                paramName: 'media',
                acceptedFiles: 'image/*, application/pdf',
                dictDefaultMessage: `<i class="fas fa-file-import"></i> Drag and drop`,
                headers: {
                    'X-CSRF-TOKEN': document.head.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                params: {
                    id: this.id,
                    collection: this.field.id,
                    model: this.model.model
                }
            }
        };
    },
    watch: {
        id() {
            if (this.id) {
                this.dropzoneOptions.params.id = this.id;
                //this.dropzoneOptions.autoProcessQueue = this.id ? true : false;
            }
        }
    },
    beforeMount() {
        this.dropzoneOptions.url = `${this.baseURL}media`;

        this.images = this.media;
        // TODO: FIX FOR BLOCK
        if (Object.keys(this.media)[0] != '0' && !_.isEmpty(this.media)) {
            this.images = [this.media];
        }
    },
    computed: {
        ...mapGetters(['baseURL', 'lng', 'form']),
        dropzone() {
            return this.$refs[`dropzone-${this.field.id}`];
        },
        maxFiles() {
            return this.images.length >= this.field.maxFiles;
        }
    },
    methods: {
        imgCols(size = 3) {
            return `col-${size}`;
        },
        getCustomProperty(image, key) {
            if (!this.field.translatable) {
                return image.custom_properties[key];
            }

            if (!(this.lng in image.custom_properties)) {
                image.custom_properties[this.lng] = {
                    alt: '',
                    title: ''
                };
            }

            return image.custom_properties[this.lng][key];
        },
        changed(value, key, image) {
            if (!this.field.translatable) {
                image.custom_properties[key] = value;
            } else {
                if (!(this.lng in image.custom_properties)) {
                    image.custom_properties[this.lng] = {
                        alt: '',
                        title: ''
                    };
                }

                image.custom_properties[this.lng][key] = value;
            }

            let job = {
                route: 'media/attributes',
                method: 'put',
                data: image
            };

            this.$store.dispatch('saveJob', job);
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
            this.images = response;

            this.$bvToast.toast(this.$t('image_uploaded'), {
                variant: 'success'
            });
        },
        uploadError(file, errorMessage, xhr) {
            this.$bvToast.toast(errorMessage, {
                variant: 'danger',
                noAutoHide: true
            });
        },
        imgPath(image) {
            return `/storage/${image.id}/${image.file_name}`;
        },
        processQueue() {
            this.$nextTick(() => {
                this.dropzone.processQueue();
            });
        },
        removeFile(file) {
            setTimeout(() => {
                this.dropzone.removeFile(file);
            }, 500);
        },
        async destroy(id, index) {
            let response = await axios.delete(`media/${id}`);
            this.$delete(this.images, index);
        },
        newOrder() {
            let payload = {
                model: 'Spatie\\MediaLibrary\\Models\\Media',
                order: _.map(this.images, 'id')
            };
            axios.put('order', payload);
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
                `#fjord-cropper-${this.field.id} .fjord-cropper__canvas`
            );
            let uploadable = true;

            // Show the cropping modal
            //
            //
            this.$bvModal.show(`fjord-cropper-${this.field.id}`);

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
                    `#fjord-cropper-${this.field.id} .fjord-cropper__preview`
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
                this.$bvModal.hide(`fjord-cropper-${this.field.id}`);
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
                        console.log('uploading');
                        done(blob);
                    });
                }

                CANVAS.html('');
                this.$bvModal.hide(`fjord-cropper-${this.field.id}`);
            });
        }
    }
};
</script>

<style lang="css"></style>
