<template>
    <b-alert
        show
        variant="warning"
        v-html="
            $t('fj.not_created', {
                relation: field.title,
                model: config.names.title.singular
            })
        "
    />
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'FieldAlertNotCreated',
    props: {
        field: {
            type: Object,
            required: true
        }
    },
    computed: {
        ...mapGetters(['config'])
    }
};
</script>
