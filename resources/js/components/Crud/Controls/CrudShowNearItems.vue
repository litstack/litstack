<template>
    <div class="indent sm">
        <b-button
            :href="`${Fjord.baseURL}${routePrefix}/${nearItems.previous}/edit`"
            :disabled="!nearItems.previous"
            variant="outline-secondary"
            size="sm"
        >
            <fa-icon icon="chevron-left" />
        </b-button>
        <b-button
            :href="`${Fjord.baseURL}${routePrefix}/${nearItems.next}/edit`"
            :disabled="!nearItems.next"
            variant="outline-secondary"
            size="sm"
        >
            <fa-icon icon="chevron-right" />
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
