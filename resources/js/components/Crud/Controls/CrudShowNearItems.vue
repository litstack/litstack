<template>
    <div class="indent sm">
        <b-button
            :href="`${Fjord.baseURL}${routePrefix}/${nearItems.previous}/edit`"
            :disabled="!nearItems.previous"
            variant="transparent"
            size="sm"
        >
            <fa-icon icon="arrow-left" />
        </b-button>
        <b-button
            :href="`${Fjord.baseURL}${routePrefix}/${nearItems.next}/edit`"
            :disabled="!nearItems.next"
            variant="transparent"
            size="sm"
        >
            <fa-icon icon="arrow-right" />
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
