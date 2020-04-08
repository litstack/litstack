<template>
    <b-alert show variant="warning">
        {{ form.config.names.title.singular }} has to be created in order to add
        <i>{{ field.title }}</i>
    </b-alert>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'FormRelationNotCreated',
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
