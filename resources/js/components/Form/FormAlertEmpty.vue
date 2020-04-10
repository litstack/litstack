<template>
    <b-alert
        show
        variant="info"
        v-html="$t('fj.no_relation_selected', { relation: field.title })"
    />
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'FormAlertEmpty',
    props: {
        field: {
            type: Object,
            required: true
        }
    },
    computed: {
        ...mapGetters(['form'])
    }
};
</script>
