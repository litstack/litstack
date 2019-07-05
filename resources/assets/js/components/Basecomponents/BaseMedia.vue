<template>
    <div class="row">
        <div class="col-4">
            <vue-dropzone
                class="fjord-dropzone"
                :ref="`dropzone-${data.id}`"
                :id="`dropzone-${data.id}`"
                :options="dropzoneOptions"
                @vdropzone-success="uploadSuccess"
            ></vue-dropzone>
        </div>
        <div class="col-8">
            <draggable v-model="images" class="row" @end="newOrder">
                <div
                    class="col-4"
                    v-for="(image, index) in images"
                    v-if="images.length > 0"
                    :key="image.id"
                >
                    <div class="card no-fx mb-3 fjord-card">
                        <div class="card-header fjord-card__1x1">
                            <img :src="imgPath(image)" class="" />
                        </div>

                        <div class="card-body">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span
                                        class="input-group-text"
                                        id="inputGroup-sizing-sm"
                                        >title</span
                                    >
                                </div>
                                <input
                                    type="text"
                                    class="form-control"
                                    @input="updateAttributes"
                                    v-model="image.custom_properties.title"
                                />
                            </div>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span
                                        class="input-group-text"
                                        id="inputGroup-sizing-sm"
                                        >alt</span
                                    >
                                </div>
                                <input
                                    type="text"
                                    class="form-control"
                                    @input="updateAttributes"
                                    v-model="image.custom_properties.alt"
                                />
                            </div>

                            <button
                                @click.prevent="destroy(image.id, index)"
                                class="btn btn-outline-danger btn-sm fjord-dropzone__delete"
                            >
                                <i class="far fa-trash-alt"></i> löschen
                            </button>
                        </div>
                    </div>
                </div>
            </draggable>
        </div>
    </div>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
import draggable from 'vuedraggable';

export default {
    name: 'BaseMedia',
    props: {
        method: {
            type: String
        },
        collection: {
            type: String
        },
        id: {
            type: [Boolean, Number]
        },
        media: {
            type: Array,
            default: function() {
                return [];
            }
        },
        data: {
            type: Object,
            required: true
        }
    },
    components: {
        vueDropzone: vue2Dropzone
    },
    data() {
        return {
            images: [],
            dropzoneOptions: {
                url: '/admin/media',
                autoProcessQueue: this.method == 'put' ? true : false,
                thumbnailWidth: 150,
                maxFilesize: 20,
                maxFiles:
                    this.data.maxFiles !== undefined ? this.data.maxFiles : 1,
                method: 'POST',
                paramName: 'media',
                acceptedFiles: 'image/*',
                dictDefaultMessage: `<i class="fas fa-file-import"></i> Drag and drop`,
                headers: {
                    'X-CSRF-TOKEN': document.head.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                params: {
                    id: this.id,
                    collection: this.data.id,
                    model: this.data.model
                }
            }
        };
    },
    mounted() {
        this.images = this.media;
    },
    watch: {
        id() {
            if (this.id) {
                this.dropzoneOptions.params.id = this.id;
                this.update();
            }
        }
    },
    methods: {
        updateAttributes() {
            this.$emit('updateAttributes', this.images);
        },
        uploadSuccess(file, response) {
            this.images = response;
            this.update();
        },
        imgPath(image) {
            return `/${image.id}/${image.file_name}`;
        },
        update() {
            let dropzone = this.$refs[`dropzone-${this.data.id}`];
            if (this.images.length < this.data.maxFiles) {
                dropzone.enable();
                dropzone.processQueue();
                $(`#dropzone-${this.data.id}`)
                    .removeClass('disabled')
                    .find('.dz-message')
                    .html('<i class="far fa-images"></i> drag and drop');

                dropzone.removeAllFiles();
            } else {
                dropzone.disable();
                $(`#dropzone-${this.data.id}`)
                    .addClass('disabled')
                    .find('.dz-message')
                    .html(
                        '<i class="far fa-images"></i> maximale Anzahl an Bildern erreicht'
                    );
            }
        },
        destroy(id, index) {
            axios
                .delete(`/admin/media/${id}`)
                .then(response => {
                    this.$delete(this.media, index);
                    this.update();
                })
                .catch(error => {
                    this.$notify({
                        group: 'general',
                        type: 'cl-error',
                        title: 'Yikes!',
                        text: `Status: ${error.response.status}, ${
                            error.response.statusText
                        } ${error.response.data.message}`,
                        duration: -1
                    });
                });
        },
        newOrder() {
            let payload = {
                model: 'AwStudio\\Fjord\\Models\\Media',
                order: _.map(this.images, 'id')
            };
            axios.put('/admin/order', payload).then(response => {
                console.log('Response: ', response.data);
            });
        }
    }
};
</script>

<style lang="css" scoped></style>
