<template>
    <b-list-group-item
        class="text-white d-flex justify-content-between search-result"
        :href="result.route"
    >
        <div class="text-light search-result__image">
            <span v-html="result.icon" class="search-result__image-icon" />
        </div>
        <div class="search-result__info">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1" v-html="result.title" />
                <small class="text-light">
                    {{ result.created_at }}
                </small>
            </div>
            <p
                class="mb-0 text-light"
                v-if="result.description"
                v-html="result.description"
            />
            <small v-if="result.hint" v-html="result.hint" class="text-light" />
        </div>
    </b-list-group-item>
</template>

<script>
export default {
    name: 'SearchResult',
    props: {
        result: {
            type: Object,
            required: true,
        },
    },
};
</script>

<style lang="scss">
.search-result {
    background: #2a353f;

    &:hover {
        background: #2f3d48;
    }
    &__image {
        width: 50px;
        height: 50px;

        &-icon {
            font-size: 1.5rem;
        }
    }

    &__info {
        flex: 1;
    }
}
</style>
