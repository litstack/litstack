<template>
    <div class="indent sm">
        <b-button
            :href="
                `${baseURL}${config.route_prefix}/${nearItems.previous}/edit`
            "
            :disabled="!nearItems.previous"
            variant="transparent"
            size="sm"
        >
            <fa-icon icon="arrow-left" />
        </b-button>
        <b-button
            :href="`${baseURL}${config.route_prefix}/${nearItems.next}/edit`"
            :disabled="!nearItems.next"
            variant="transparent"
            size="sm"
        >
            <fa-icon icon="arrow-right" />
        </b-button>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudShowNearItems',
    props: {
        nearItems: {
            type: Object
        },
        config: {
            type: Object,
            required: true
        }
    },
    computed: {
        ...mapGetters(['baseURL'])
    }
};
</script>
