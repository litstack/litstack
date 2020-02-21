<template>
    <draggable v-model="sortable" class="row" @end="newOrder">
        <div
            :class="imgCols(field.image_size)"
            v-for="(image, index) in sortable"
            v-if="sortable.length > 0"
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
                        v-b-modal="`fjord-image-${field.id}-${image.id}`"
                    >
                        <i class="fas fa-edit"></i>
                    </b-button>
                </div>
                <fj-form-media-modal
                    :index="index"
                    :field="field"
                    :image="image"
                    :imgPath="imgPath"
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
            this.$bvToast.toast(this.$t('order_changed'), {
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
