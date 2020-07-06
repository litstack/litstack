<template>
    <b-modal
        :id="`fj-image-${field.id}-${image.id}`"
        size="full"
        class="fj-image-modal"
        :title="`${image.name}`"
    >
        <div class="row full-height" style="height: 100%">
            <fj-col
                :width="field.type == 'image' ? 8 : 12"
                class="full-height"
                style="height: 100%"
            >
                <img
                    v-if="field.type == 'image'"
                    :src="imgPath(image)"
                    class="fj-image-preview"
                />
                <embed
                    v-if="field.type == 'file'"
                    :src="imgPath(image)"
                    type="application/pdf"
                    class="fj-image-preview"
                />
            </fj-col>
            <fj-col :width="4" v-if="field.type == 'image'">
                <div class="mb-2">
                    <label class="mb-1">
                        Title
                    </label>
                    <b-badge v-if="field.translatable" variant="primary">
                        <small>{{ language }}</small>
                    </b-badge>

                    <b-input
                        v-bind:readonly="field.readonly"
                        :value="getCustomProperty(image, 'title')"
                        class="dark"
                        @input="changed($event, 'title', image)"
                    />
                </div>
                <div>
                    <label class="mb-1">Alt</label>
                    <b-badge v-if="field.translatable" variant="primary">
                        <small>{{ language }}</small>
                    </b-badge>
                    <b-input
                        v-bind:readonly="field.readonly"
                        :value="getCustomProperty(image, 'alt')"
                        class="dark"
                        @input="changed($event, 'alt', image)"
                    />
                </div>
            </fj-col>
        </div>
        <div slot="modal-footer" class="w-100 d-flex justify-content-between">
            <div>
                <b-button
                    @click.prevent="destroy(image.id, index)"
                    variant="danger"
                    v-if="!field.readonly"
                >
                    <i class="far fa-trash-alt"></i>
                    {{ $t('fj.delete') }}
                </b-button>
            </div>
            <div>
                <b-button
                    class="fj-save-button"
                    variant="primary"
                    :disabled="!canSave"
                    @click="Fjord.bus.$emit('save')"
                >
                    {{ $t('fj.save') }}
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
            required: true
        },
        field: {
            type: Object,
            required: true
        },
        image: {
            type: Object,
            required: true
        },
        imgPath: {
            type: Function,
            required: true
        },
        model: {
            required: true,
            type: Object
        },
        modelId: {
            required: true
        }
    },
    methods: {
        /**
         * Handle custom property input changed.
         */
        changed(value, key, image) {
            if (!this.field.translatable) {
                image.custom_properties[key] = value;
            } else {
                if (!(this.language in image.custom_properties)) {
                    image.custom_properties[this.language] = {
                        alt: '',
                        title: ''
                    };
                }

                image.custom_properties[this.language][key] = value;
            }

            let job = {
                route: `${this.field.route_prefix}/media`,
                method: 'put',
                params: {
                    payload: { custom_properties: image.custom_properties },
                    field_id: this.field.id,
                    media_id: this.image.id
                }
            };

            this.$store.dispatch('saveJob', job);
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
                    title: ''
                };
            }

            return image.custom_properties[this.language][key];
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
        }
    },
    computed: {
        ...mapGetters(['language', 'canSave'])
    }
};
</script>

<style lang="scss" scoped>
.fj-image-preview {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
</style>
