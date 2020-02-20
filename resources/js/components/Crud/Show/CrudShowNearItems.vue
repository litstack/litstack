<template>
    <div class="indent sm">
        <b-button
            :href="
                `${baseURL}${form.config.names.table}/${nearItems.previous}/edit`
            "
            :disabled="!nearItems.previous"
            variant="transparent"
            size="sm"
        >
            <fa-icon icon="arrow-left" />
        </b-button>
        <b-button
            :href="
                `${baseURL}${form.config.names.table}/${nearItems.next}/edit`
            "
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
        }
    },
    computed: {
        ...mapGetters(['baseURL', 'form'])
    }
};
</script>
