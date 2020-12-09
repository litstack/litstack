<template>
    <b-modal
        :id="`lit-image-${field.id}-${image.id}`"
        size="full"
        class="lit-image-modal"
        :title="`${image.name}`"
    >
        <div class="row full-height" style="height: 100%;">
            <lit-col
                :width="field.type == 'image' ? 8 : 12"
                class="full-height"
                style="height: 100%;"
            >
                <template v-if="field.type == 'image'">
                    <img
                        v-if="!croppable || !cropping"
                        :src="imageUrl"
                        class="lit-image-preview"
                    />

                    <lit-field-media-cropper
                        :style="`display: ${cropping ? 'block' : 'none'}`"
                        v-if="file"
                        :file="file"
                        :field="field"
                        :image="image"
                        ref="cropper"
                        @cropped="cropped($event)"
                    />
                </template>

                <embed
                    v-if="field.type == 'file'"
                    :src="imgPath(image)"
                    type="application/pdf"
                    class="lit-image-preview"
                />
            </lit-col>

            <lit-col :width="4" v-if="field.type == 'image'">
                <div
                    class="justify-content-between mb-3"
                    :style="`display:${cropping ? 'flex' : 'none'}`"
                >
                    <b-button @click="cancelCrop">
                        {{ __('base.undo_changes').capitalize() }}
                    </b-button>
                    <b-button variant="primary" @click="toggleCrop">
                        {{ __('base.done').capitalize() }}
                    </b-button>
                </div>
                <div
                    class="r4x3 mb-2"
                    :style="`display:${cropping ? 'block' : 'none'}`"
                >
                    <div
                        class="lit-cropper__preview"
                        ref="cropperPreview"
                    ></div>
                </div>

                <template v-if="!cropping">
                    <div class="mb-2">
                        <div class="mb-2 d-flex justify-content-between">
                            <label class="m-0">Title</label>
                            <b-badge
                                v-if="field.translatable"
                                variant="secondary"
                            >
                                <small>{{ language }}</small>
                            </b-badge>
                        </div>

                        <b-input
                            v-bind:readonly="field.readonly"
                            :value="getCustomProperty(image, 'title')"
                            class="dark"
                            @input="changed($event, 'title', image)"
                        />
                        <span class="form-text" style="color: #98959c;">
                            {{
                                __(
                                    'crud.fields.media.messages.image_title_hint'
                                )
                            }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <div class="mb-2 d-flex justify-content-between">
                            <label class="m-0">Alt</label>
                            <b-badge
                                v-if="field.translatable"
                                variant="secondary"
                            >
                                <small>{{ language }}</small>
                            </b-badge>
                        </div>
                        <b-input
                            v-bind:readonly="field.readonly"
                            :value="getCustomProperty(image, 'alt')"
                            class="dark"
                            @input="changed($event, 'alt', image)"
                        />
                        <span class="form-text" style="color: #98959c;">
                            {{
                                __('crud.fields.media.messages.image_alt_hint')
                            }}
                        </span>
                    </div>
                </template>
            </lit-col>
        </div>
        <div slot="modal-footer" class="w-100 d-flex justify-content-between">
            <div>
                <b-button
                    @click.prevent="destroy(image.id, index)"
                    variant="danger"
                    v-if="!field.readonly"
                >
                    <i class="far fa-trash-alt"></i>
                    {{ __('base.delete').capitalize() }}
                </b-button>
            </div>
            <b-button
                v-if="croppable"
                @click="toggleCrop"
                variant="primary"
                :disabled="cropping"
            >
                <lit-fa-icon icon="crop-alt" />
                {{ __('base.crop').capitalize() }}
            </b-button>
            <div class="d-flex">
                <lit-crud-language
                    class="mr-2"
                    v-if="this.field.translatable"
                />
                <b-button
                    class="lit-save-button"
                    variant="primary"
                    :disabled="!canSave"
                    @click="Lit.bus.$emit('save')"
                >
                    {{ __('base.save').capitalize() }}
                </b-button>
            </div>
        </div>
    </b-modal>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FieldMediaModal',
    props: {
        index: {
            type: Number,
            required: true,
        },
        field: {
            type: Object,
            required: true,
        },
        image: {
            type: Object,
            required: true,
        },
        imgPath: {
            type: Function,
            required: true,
        },
        model: {
            required: true,
            type: Object,
        },
        modelId: {
            required: true,
        },
    },
    data() {
        return {
            original: {},
            file: null,
            cropping: false,
            url: null,
        };
    },
    beforeMount() {
        this.setFileObject();

        this.setOriginal();
        Lit.bus.$on('saved', () => {
            this.setOriginal();
        });
    },
    mounted() {
        this.$root.$on('bv::modal::hidden', (bvEvent, modalId) => {
            if (!this.cropping) {
                return;
            }

            this.cancelCrop();
        });
    },
    methods: {
        cropped(crop) {
            this.url = this.$refs.cropper.cropper
                .getCroppedCanvas()
                .toDataURL('image/jpeg', 1);
            this.changed(crop, 'crop', this.image);
            this.$forceUpdate();
        },
        cancelCrop() {
            this.changed(this.original.crop, 'crop', this.image);
            this.url = null;
            this.toggleCrop();
        },
        toggleCrop() {
            if (this.cropping) {
            } else {
                this.$refs.cropper.$emit(
                    'crop',
                    this.image.custom_properties.crop
                );
            }

            this.cropping = !this.cropping;
        },
        async setFileObject() {
            let response = await axios.get(this.image.original_url, {
                responseType: 'blob',
            });
            this.file = new File([response.data], 'original');
        },
        dataURLtoFile(dataurl, filename) {
            var arr = dataurl.split(','),
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]),
                n = bstr.length,
                u8arr = new Uint8Array(n);

            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }

            return new File([u8arr], filename, { type: mime });
        },

        setOriginal() {
            if (this.image.custom_properties) {
                this.original = Lit.clone(this.image.custom_properties);
            }
        },

        getDefaultProperties() {
            return { alt: '', title: '' };
        },

        /**
         * Handle custom property input changed.
         */
        changed(value, key, image) {
            if (!this.field.translatable || key == 'crop') {
                image.custom_properties[key] = value;
            } else {
                if (!(this.language in image.custom_properties)) {
                    image.custom_properties[
                        this.language
                    ] = this.getDefaultProperties();
                }

                image.custom_properties[this.language][key] = value;
            }

            let job = {
                route: `${this.field.route_prefix}/media`,
                method: 'put',
                params: this.qualifyParams({
                    payload: { custom_properties: image.custom_properties },
                    media_id: this.image.id,
                }),
            };

            if (this.hasValueChanged(image.custom_properties)) {
                this.$store.commit('ADD_SAVE_JOB', job);
            } else {
                this.$store.commit('REMOVE_SAVE_JOB', job);
            }
        },

        /**
         * Get custom property for image by propery name.
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
         * Determines if the custom properties have changed.
         */
        hasValueChanged(value) {
            // TODO:
            return true;
        },

        /**
         * Handle delet image click.
         */
        async destroy(id, index) {
            this.$emit('delete');
        },

        /**
         * Get media url for image id.
         */
        getMediaUrl(id) {
            return `${this.field.route_prefix}/media/${id}`;
        },

        /**
         * Get qualified params.
         */
        qualifyParams(params) {
            params = {
                field_id: this.field.id,
                ...(this.field.params || {}),
                ...params,
            };

            if (params.field_id != this.field.id) {
                params.child_field_id = this.field.id;
            }

            return params;
        },
    },

    computed: {
        ...mapGetters(['language', 'canSave']),

        /**
         * Croppable
         */
        croppable() {
            return this.field.crop !== false && this.field.crop !== undefined;
        },

        /**
         * Image url.
         */
        imageUrl() {
            if (this.url !== null) {
                return this.url;
            }

            return this.imgPath(this.image);
        },
    },
};
</script>

<style lang="scss">
.lit-image-preview {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
</style>
