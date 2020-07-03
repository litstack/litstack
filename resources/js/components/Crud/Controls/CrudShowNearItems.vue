<template>
    <div class="fj-near-items">
        <b-button
            :href="`${Fjord.baseURL}${routePrefix}/${previous}/edit`"
            :disabled="!previous"
            variant="outline-secondary"
            size="sm"
            class="btn-square"
        >
            <fa-icon icon="chevron-left" class="fj-near-items__prev" />
        </b-button>
        <b-button
            :href="`${Fjord.baseURL}${routePrefix}/${next}/edit`"
            :disabled="!next"
            variant="outline-secondary"
            size="sm"
            class="btn-square"
        >
            <fa-icon icon="chevron-right" class="fj-near-items__next" />
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
            required: true
        }
    }
};
</script>

<style lang="scss" scoped>
.fj-near-items {
    &__prev {
        margin-right: 2px;
    }
    &__next {
        margin-left: 2px;
    }
}
</style>
