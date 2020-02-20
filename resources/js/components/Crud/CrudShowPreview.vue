<template>
    <b-button
        variant="primary"
        size="sm"
        v-if="formConfig.preview_route"
        v-b-modal.fj-page-preview
    >
        <fa-icon icon="eye" /> {{ $t('preview') }}

        <fj-page-preview
            :route="formConfig.preview_route"
            v-if="formConfig.preview_route"
        />
    </b-button>
</template>

<script>
export default {
    name: 'CrudShowPreview',
    props: {
        formConfig: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        }
    }
};
</script>
