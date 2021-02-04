<template>
    <draggable
        v-model="sortable"
        :class="{
            'lit-image-list': true,
            'lit-image-list__preserve': field.expand,
            'mb-4': field.readonly || images.length == 0,
        }"
        v-bind:disabled="field.readonly"
        @end="newOrder"
        handle=".lit-draggable__dragbar"
    >
        <div
            :class="{
                'lit-image-card': true,
                'lit-image-card__litirst-big': field.firstBig && index == 0,
            }"
            v-for="(image, index) in sortable"
            :key="image.id"
        >
            <div class="lit-image-card__controls">
                <b-button
                    v-if="!field.readonly && field.maxFiles > 1"
                    size="sm"
                    variant="link"
                    class="text-secondary lit-image-card__controls_drag lit-draggable__dragbar"
                >
                    <lit-fa-icon icon="grip-vertical" />
                </b-button>
                <b-button
                    v-if="!field.readonly"
                    size="sm"
                    variant="link"
                    class="text-secondary lit-image-card__controls_delete"
                    @click="deleteImage(image, index)"
                >
                    <lit-fa-icon icon="trash" />
                </b-button>
                <b-button
                    size="sm"
                    variant="link"
                    class="text-secondary lit-image-card__controls_edit"
                    v-b-modal="`lit-image-${field.id}-${image.id}`"
                >
                    <i :class="`fas fa-${field.readonly ? 'eye' : 'edit'}`"></i>
                </b-button>
            </div>
            <div
                :class="{
                    'lit-image-card__image': true,
                }"
            >
                <lit-field-media-image
                    :field="field"
                    :image="image"
                    v-if="field.type == 'image'"
                />
                <lit-fa-icon
                    v-else
                    :icon="['far', 'file']"
                    class="text-secondary"
                    size="2x"
                />
            </div>
            <lit-field-media-modal
                :index="index"
                :field="field"
                :image="image"
                :imgPath="imgPath"
                :model="model"
                :model-id="modelId"
                @delete="deleteImage(image, index)"
            />
            <b-modal
                :id="deleteImageModalId(image)"
                size="md"
                :title="
                    __('base.item_delete', {
                        item: __('base.image'),
                    }).capitalize() + '?'
                "
            >
                {{ __('base.messages.are_you_sure') }}

                <template v-slot:modal-footer>
                    <b-button
                        variant="secondary"
                        size="sm"
                        class="float-right"
                        @click="$bvModal.hide(deleteImageModalId(image))"
                    >
                        {{ __('base.cancel').capitalize() }}
                    </b-button>
                    <a
                        href="#"
                        @click.prevent="
                            _deleteImage(image, index);
                            $bvModal.hide(deleteImageModalId(image));
                        "
                        class="lit-trash btn btn-danger btn-sm"
                    >
                        <lit-fa-icon icon="trash" />
                        {{ __('base.delete').capitalize() }}
                    </a>
                </template>
            </b-modal>
        </div>

        <slot name="busy" />
        <slot name="drop" />
    </draggable>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FieldMediaImages',
    props: {
        images: {
            type: Array,
        },
        field: {
            required: true,
            type: Object,
        },
        modelId: {
            required: true,
        },
        model: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            sortable: this.images,
        };
    },
    computed: {
        ...mapGetters(['form']),
    },
    methods: {
        /**
         * Create new order.
         */
        async newOrder() {
            let response = await this.sendNewOrder({
                ids: _.map(this.sortable, 'id'),
            });

            if (!response) {
                return;
            }

            this.$bvToast.toast(this.__('base.messages.order_changed'), {
                variant: 'success',
            });
            this.$emit('newOrder');
            Lit.bus.$emit('field:updated', 'image:ordered');
        },

        /**
         * Send new order request.
         */
        async sendNewOrder(payload) {
            try {
                return await axios.post(
                    `${this.field.route_prefix}/media/order`,
                    this.qualifyParams({
                        ...payload,
                    })
                );
            } catch (e) {
                console.log(e);
            }
        },

        /**
         * Image path.
         */
        imgPath(image) {
            if (image.mime_type == 'image/svg+xml') {
                return image.original_url;
            }
            if (image.showOrignial == true) {
                return image.original_url;
            }
            return image.url || image.original_url;
        },

        /**
         * Handle delete image button click.
         */
        deleteImage(image, index) {
            this.$bvModal.show(this.deleteImageModalId(image));
        },

        /**
         * Delete image.
         */
        async _deleteImage(image, index) {
            let response = await this.sendDeleteImage(image);

            if (!response) {
                return;
            }

            this.$delete(this.sortable, index);
            this.$emit('deleted');
            Lit.bus.$emit('field:updated', 'image:deleted');
        },

        /**
         * Send delete image request.
         */
        async sendDeleteImage(media) {
            try {
                return await axios.post(
                    `${this.field.route_prefix}/media/destroy`,
                    this.qualifyParams({
                        media_id: media.id,
                    })
                );
            } catch (e) {
                console.log(e);
            }
        },

        /**
         * Delete image modal id by image.
         */
        deleteImageModalId(image) {
            return `lit-delete-image-${this.field.id}-${image.id}`;
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
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
.lit-image-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(8rem, 1fr));
    grid-auto-rows: 1fr;
    grid-gap: $grid-gutter-width / 2;

    > *:first-child {
        grid-row: 1 / 1;
        grid-column: 1 / 1;
    }
    &::before {
        content: '';
        width: 0;
        padding-bottom: 100%;
        grid-row: 1 / 1;
        grid-column: 1 / 1;
    }

    &__preserve {
        display: block;

        .lit-dropzone-busy {
            margin-bottom: 1rem;
        }

        .lit-image-card {
            &__image img {
                position: relative;
                height: auto;
            }
        }
    }
}
.dropzone.dz-started .dz-message {
    display: block !important;
}
.dz-image-preview {
    display: none !important;
}

.lit-image-card {
    border-radius: $border-radius;
    overflow: hidden;
    position: relative;
    //border: $input-border-width solid $input-border-color;
    border: none;
    background: $body-bg;
    display: flex;

    &__litirst-big {
        grid-column: 1 / span 2 !important;
        grid-row: 1 / span 2 !important;
    }

    &__image {
        display: flex;
        overflow: hidden;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;

        img {
            position: absolute;
            z-index: 1;
            height: 100%;
            width: 100%;
            object-fit: cover;
        }
    }

    &__controls {
        border-radius: $border-radius;
        overflow: hidden;
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        z-index: 2;

        > * {
            opacity: 0;
            cursor: pointer;
            position: absolute;
        }

        &:hover {
            &::before,
            &::after {
                content: '';
                display: block;
                width: 100%;
                height: 2rem;
                background: rgba(black, 0.7);
                position: absolute;
                backdrop-filter: blur(5px);
                z-index: -1;
            }

            &::before {
                top: 0;
                border-top-left-radius: $border-radius;
                border-top-right-radius: $border-radius;
            }
            &::after {
                bottom: 0;
                border-bottom-left-radius: $border-radius;
                border-bottom-right-radius: $border-radius;
            }

            > * {
                opacity: 1;
                color: white !important;
            }
        }

        &_drag {
            left: 0;
            top: $input-padding-y-sm / 2;
            &:hover {
                cursor: grab;
            }
        }
        &_edit {
            right: 0;
            top: $input-padding-y-sm / 2;
        }

        &_delete {
            right: 0;
            bottom: $input-padding-y-sm / 2;
        }
    }
}
</style>
