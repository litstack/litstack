<template>
    <b-button
        variant="primary"
        size="sm"
        v-if="form.config.preview_route"
        v-b-modal.fj-page-preview
    >
        <fa-icon icon="eye" /> {{ $t('preview') }}

        <fj-page-preview
            :route="form.config.preview_route"
            v-if="form.config.preview_route"
        />
    </b-button>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudShowPreview',
    props: {
        model: {
            required: true,
            type: Object
        }
    },
    computed: {
        ...mapGetters(['form'])
    }
};
</script>
