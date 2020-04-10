<template>
    <b-alert
        show
        variant="warning"
        v-html="
            $t('fj.not_created', {
                relation: field.title,
                model: form.config.names.title.singular
            })
        "
    />
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'FormAlertNotCreated',
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
