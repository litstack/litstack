<template>
    <b-modal
        :id="`fjord-image-${field.id}-${image.id}`"
        size="xl"
        class="fjord-image-modal"
        :title="`${image.name}`"
        :static="true"
    >
        <div class="row">
            <div class="col-7">
                <img :src="imgPath(image)" class="img-fluid" />
            </div>
            <div class="col-5">
                <div class="mb-2">
                    <label class="mb-1">
                        Title
                    </label>
                    <b-badge v-if="field.translatable" variant="primary">
                        <small>{{ language }}</small>
                    </b-badge>

                    <b-input
                        v-bind:readonly="readonly"
                        :size="'sm'"
                        :value="getCustomProperty(image, 'title')"
                        @input="changed($event, 'title', image)"
                    />
                </div>
                <div>
                    <label class="mb-1">Alt</label>
                    <b-badge v-if="field.translatable" variant="primary">
                        <small>{{ language }}</small>
                    </b-badge>
                    <b-input
                        v-bind:readonly="readonly"
                        :size="'sm'"
                        :value="getCustomProperty(image, 'alt')"
                        @input="changed($event, 'alt', image)"
                    />
                </div>
            </div>
        </div>
        <div slot="modal-footer" class="w-100 d-flex justify-content-between">
            <button
                @click.prevent="destroy(image.id, index)"
                class="btn btn-danger btn-sm"
                v-if="!readonly"
            >
                <i class="far fa-trash-alt"></i>
                delete
            </button>
            <div v-else />
            <button
                @click.prevent="
                    $bvModal.hide(`fjord-image-${field.id}-${image.id}`)
                "
                class="btn btn-secondary btn-sm"
            >
                close
            </button>
        </div>
    </b-modal>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FormMediaModal',
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
        readonly: {
            required: true,
            type: Boolean
        }
    },
    methods: {
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
                route: this.getMediaUrl(image.id),
                method: 'put',
                data: { custom_properties: image.custom_properties }
            };

            this.$store.dispatch('saveJob', job);
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
        async destroy(id, index) {
            let response = await axios.delete(this.getMediaUrl(id));
            this.$emit('delete', index);
        },
        getMediaUrl(id) {
            if (this.model.model != 'Fjord\\Crud\\Models\\FormBlock') {
                return `${this.form.config.route_prefix}/${this.model.id}/media/${id}`;
            }
            return `${this.form.config.route_prefix}/${this.model.model_id}/blocks/${this.model.id}/media/${id}`;
        }
    },
    computed: {
        ...mapGetters(['language', 'form'])
    }
};
</script>
