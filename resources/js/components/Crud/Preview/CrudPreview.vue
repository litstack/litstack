<template>
    <b-button
        variant="primary"
        size="sm"
        v-b-modal.fj-page-preview
        v-if="config.preview_route"
    >
        <fa-icon icon="eye" />

        {{ __('fj.preview') }}

        <fj-page-preview :show="true" :route="config.preview_route" />
    </b-button>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudPreview',
    props: {
        config: {
            required: true,
            type: Object
        }
    }
};
</script>
