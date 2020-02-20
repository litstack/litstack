<template>
    <b-button
        variant="transparent"
        size="sm"
        v-if="formConfig.preview_route"
        v-b-modal.fj-page-preview>
        <fa-icon icon="eye"/> Preview

        <fj-page-preview :route="formConfig.preview_route" v-if="formConfig.preview_route"/>
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
}
</script>
