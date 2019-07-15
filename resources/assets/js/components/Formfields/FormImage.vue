<template>
    <BaseFormitem :field="item">
        <vue-dropzone
            class="fjord-dropzone"
            :ref="`dropzone`"
            :id="`dropzone`"
            :options="dropzoneOptions"
            @vdropzone-success="uploadSuccess"
        ></vue-dropzone>
        <div class="row">
            <div
                class="col-6 col-sm-4 col-md-3 col-lg-2"
                v-for="(img, index) in images"
            >
                <a
                    href="#"
                    @click.prevent="show(index)"
                    class="fjord-thumbnail"
                >
                    <img
                        :src="path(img)"
                        class="img-fluid img-thumbnail mt-2 fjord-thumbnail"
                    />
                </a>
            </div>
        </div>
        <modal :name="`gallery_${id}`" :width="'80%'" :height="'80%'">
            <div class="fjord-gallery" v-if="image">
                <div class="row">
                    <div class="col-6">
                        <img
                            :src="path(image)"
                            class="img-fluid img-thumbnail"
                        />
                    </div>
                    <div class="col-6">
                        <pre>{{ image }}</pre>
                    </div>
                </div>
            </div>
        </modal>
    </BaseFormitem>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone';
import 'vue2-dropzone/dist/vue2Dropzone.min.css';

export default {
    name: 'FormImage',
    props: ['item', 'value', 'data', 'id'],
    components: {
        vueDropzone: vue2Dropzone
    },
    data() {
        return {
            images: this.value,
            image: null,
            dropzoneOptions: {
                url: '/admin/media',
                thumbnailWidth: 150,
                maxFilesize: 20,
                autoProcessQueue: true,
                maxFiles: 1,
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
                    collection: 'image',
                    model: this.data.model
                }
            }
        };
    },
    beforeMount() {
        this.model = this.value;
    },
    methods: {
        uploadSuccess(file, response) {
            this.$emit('input', 'success');
            for (var i = 0; i < response.length; i++) {
                this.images = response;
            }
            this.$notify({
                group: 'general',
                type: 'cl-success',
                title: 'Hurray!',
                text: 'Bilder hochgeladen',
                duration: 3000
            });
        },
        path(img) {
            return `/storage/${img.id}/${img.file_name}`;
        },
        show(index) {
            this.image = this.images[index];
            this.$modal.show(`gallery_${this.id}`);
        },
        hide() {
            this.$modal.hide(`gallery_${this.id}`);
        }
    }
};
</script>

<style lang="scss">
.fjord-gallery {
    padding: 40px;
}
</style>
