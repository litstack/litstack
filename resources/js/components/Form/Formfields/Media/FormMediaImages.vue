<template>
    <draggable
        v-model="sortable"
        class="row"
        :options="{ disabled: readonly }"
        @end="newOrder"
        v-if="sortable.length > 0"
    >
        <div
            :class="imgCols(field.image_size)"
            v-for="(image, index) in sortable"
            :key="image.id"
        >
            <div :class="{ 'mb-3': !readonly, 'card no-fx fjord-card': true }">
                <div
                    :class="{
                        'fjord-card__1x1': field.square,
                        'fjord-card__image': true
                    }"
                >
                    <img :src="imgPath(image)" class />
                </div>
                <div class="text-right">
                    <b-button
                        size="sm"
                        variant="link"
                        class="text-secondary"
                        v-b-modal="`fjord-image-${field.id}-${image.id}`"
                    >
                        <i :class="`fas fa-${readonly ? 'eye' : 'edit'}`"></i>
                    </b-button>
                </div>
                <fj-form-media-modal
                    :index="index"
                    :field="field"
                    :image="image"
                    :imgPath="imgPath"
                    :model="model"
                    :readonly="readonly"
                    @delete="deleteImage"
                />
            </div>
        </div>
    </draggable>
</template>

<script>
export default {
    name: 'FormMediaImages',
    props: {
        images: {
            type: Array
        },
        field: {
            required: true,
            type: Object
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
    data() {
        return {
            sortable: this.images
        };
    },
    methods: {
        async newOrder() {
            let payload = {
                model: 'Spatie\\MediaLibrary\\Models\\Media',
                order: _.map(this.sortable, 'id')
            };
            await axios.put('order', payload);
            this.$bvToast.toast(this.$t('fj.order_changed'), {
                variant: 'success'
            });
        },
        imgCols(size = 3) {
            return `col-${size}`;
        },
        imgPath(image) {
            return `/storage/${image.id}/${image.file_name}`;
        },
        deleteImage(index) {
            this.$delete(this.sortable, index);
        }
    }
};
</script>
