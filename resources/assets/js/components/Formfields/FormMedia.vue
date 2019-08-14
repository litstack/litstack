<template>
    <div class="row">
        <div class="col-4">
            <vue-dropzone
                class="fjord-dropzone"
                :ref="`dropzone-${field.id}`"
                :id="`dropzone-${field.id}`"
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
import { mapGetters } from 'vuex'

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
        return {
            images: [],
            dropzoneOptions: {
                url: `${this.baseURL}media`,
                autoProcessQueue: true,
                thumbnailWidth: 150,
                maxFilesize: 20,
                maxFiles: this.field.maxFiles,
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
                this.dropzoneOptions.autoProcessQueue = this.id ? true : false;
                this.update();
            }
        }
    },
    beforeMount() {
        this.images = this.media
        // TODO: FIX FOR BLOCK
        if(typeof this.media == typeof {} && !_.isEmpty(this.media)) {
            this.images = [this.media]
        }
    },
    mounted() {
        this.checkMaxFiles()
    },
    computed: {
        ...mapGetters(['baseURL']),
        dropzone() {
            return this.$refs[`dropzone-${this.field.id}`]
        }
    },
    methods: {
        checkMaxFiles() {
            if (this.images.length >= this.field.maxFiles) {
                this.dropzone.disable();
                $(`#dropzone-${this.field.id}`)
                    .addClass('disabled')
                    .find('.dz-message')
                    .html(
                        '<i class="far fa-images"></i> maximale Anzahl an Bildern erreicht'
                    );
                return true;
            }

            return false

        },
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
            if(this.checkMaxFiles()) {
                return;
            }

            if (!this.id) {
                return;
            }


            this.dropzone.processQueue();
            $(`#dropzone-${this.field.id}`)
                .removeClass('disabled')
                .find('.dz-message')
                .html('<i class="far fa-images"></i> drag and drop');

            this.dropzone.removeAllFiles();
            this.dropzone.enable()
        },
        async destroy(id, index) {
            let response = await axios.delete(`media/${id}`)
            this.$delete(this.images, index);
            this.update();
        },
        newOrder() {
            let payload = {
                model: 'AwStudio\\Fjord\\Models\\Media',
                order: _.map(this.images, 'id')
            };
            axios.put('order', payload).then(response => {
                console.log('Response: ', response.data);
            });
        }
    }
};
</script>

<style lang="css"></style>
