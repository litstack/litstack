<template>
    <draggable
        v-model="sortable"
        class="fj-image-list mb-4"
        :options="{ disabled: field.readonly }"
        @end="newOrder"
        handle=".fj-draggable__dragbar"
        v-if="sortable.length > 0"
    >
        <div
            :class="{
                'fj-image-card': true,
                'fj-image-card__first-big': field.firstBig && index == 0
            }"
            v-for="(image, index) in sortable"
            :key="image.id"
        >
            <div class="fj-image-card__controls">
                <b-button
                    v-if="!field.readonly"
                    size="sm"
                    variant="link"
                    class="text-secondary fj-image-card__controls_drag fj-draggable__dragbar"
                >
                    <fa-icon icon="grip-vertical" />
                </b-button>
                <b-button
                    v-if="!field.readonly"
                    size="sm"
                    variant="link"
                    class="text-secondary fj-image-card__controls_delete"
                    @click="deleteImage(image, index)"
                >
                    <fa-icon icon="trash" />
                </b-button>
                <b-button
                    size="sm"
                    variant="link"
                    class="text-secondary fj-image-card__controls_edit"
                    v-b-modal="`fj-image-${field.id}-${image.id}`"
                >
                    <i :class="`fas fa-${field.readonly ? 'eye' : 'edit'}`"></i>
                </b-button>
            </div>
            <div
                :class="{
                    'fj-image-card__image': true
                }"
            >
                <img :src="imgPath(image)" class />
            </div>
            <fj-field-media-modal
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
                        item: __('base.image')
                    }).capitalize() + '?'
                "
                :static="true"
            >
                {{ __('messages.cant_be_undone') }}

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
                        class="fj-trash btn btn-danger btn-sm"
                    >
                        <fa-icon icon="trash" />
                        {{ __('base.delete').capitalize() }}
                    </a>
                </template>
            </b-modal>
        </div>

        <slot name="drop" />
    </draggable>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FieldMediaImages',
    props: {
        images: {
            type: Array
        },
        field: {
            required: true,
            type: Object
        },
        modelId: {
            required: true
        },
        model: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            sortable: this.images
        };
    },
    computed: {
        ...mapGetters(['form'])
    },
    methods: {
        async newOrder() {
            let payload = {
                collection: this.field.id,
                ids: _.map(this.sortable, 'id')
            };
            await axios.put(this.getMediaUrl('order'), payload);
            this.$bvToast.toast(this.$t('fj.order_changed'), {
                variant: 'success'
            });
        },
        getMediaUrl(key) {
            return `${this.field.route_prefix}/media/${key}`;
        },
        imgCols(size = 3) {
            return `col-${size}`;
        },
        imgPath(image) {
            return `/storage/${image.id}/${image.file_name}`;
        },
        deleteImage(image, index) {
            this.$bvModal.show(this.deleteImageModalId(image));
        },
        async _deleteImage(image, index) {
            let response = await axios.delete(this.getMediaUrl(image.id));
            this.$delete(this.sortable, index);
        },
        deleteImageModalId(image) {
            return `fj-delete-image-${this.field.id}-${image.id}`;
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';
.fj-image-list {
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
}

.fj-image-card {
    border-radius: $border-radius;
    overflow: hidden;
    position: relative;
    border: $input-border-width solid $input-border-color;
    background: $body-bg;
    display: flex;

    &__first-big {
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
            max-width: 100%;
            max-height: 100%;
        }
    }

    &__controls {
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
            background: rgba($info, 0.1);

            > * {
                opacity: 1;
            }
        }

        &_edit {
            top: $input-padding-y-sm;
            right: $input-padding-y-sm;
        }

        &_delete {
            right: $input-padding-y-sm;
            bottom: $input-padding-y-sm;
        }

        &_drag {
            left: $input-padding-y-sm;
            top: $input-padding-y-sm;
        }
    }
}
</style>
