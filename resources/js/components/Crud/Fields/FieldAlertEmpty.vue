<template>
    <b-alert
        show
        variant="info"
        v-html="__('base.no_item_selected', { item: field.title })"
    />
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'FieldAlertEmpty',
    props: {
        field: {
            type: Object,
            required: true,
        },
    },
    computed: {
        ...mapGetters(['config']),
    },
};
</script>
