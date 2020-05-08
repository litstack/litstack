<template>
    <div class="fj-near-items">
        <b-button
            :href="`${Fjord.baseURL}${routePrefix}/${nearItems.previous}/edit`"
            :disabled="!nearItems.previous"
            variant="outline-secondary"
            size="sm"
            class="btn-sm-square"
        >
            <fa-icon icon="chevron-left" class="fj-near-items__prev" />
        </b-button>
        <b-button
            :href="`${Fjord.baseURL}${routePrefix}/${nearItems.next}/edit`"
            :disabled="!nearItems.next"
            variant="outline-secondary"
            size="sm"
            class="btn-sm-square"
        >
            <fa-icon icon="chevron-right" class="fj-near-items__next" />
        </b-button>
    </div>
</template>

<script>
export default {
    name: 'CrudShowNearItems',
    props: {
        nearItems: {
            type: Object
        },
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
