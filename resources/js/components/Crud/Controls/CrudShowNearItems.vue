<template>
    <div class="lit-near-items d-inline-block">
        <b-button
            :href="`${Lit.baseURL}${routePrefix}/${previous}`"
            :disabled="!previous"
            variant="transparent"
            size="sm"
            class="btn-square"
        >
            <lit-fa-icon icon="arrow-left" class="lit-near-items__prev" />
        </b-button>
        <b-button
            :href="`${Lit.baseURL}${routePrefix}/${next}`"
            :disabled="!next"
            variant="transparent"
            size="sm"
            class="btn-square"
        >
            <lit-fa-icon icon="arrow-right" class="lit-near-items__next" />
        </b-button>
    </div>
</template>

<script>
export default {
    name: 'CrudShowNearItems',
    props: {
        previous: {},
        next: {},
        routePrefix: {
            type: String,
            required: true,
        },
    },
};
</script>

<style lang="scss" scoped>
.lit-near-items {
    &__prev {
        margin-right: 2px;
    }
    &__next {
        margin-left: 2px;
    }
}
</style>
