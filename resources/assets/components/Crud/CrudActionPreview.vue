<template>
    <b-button
        variant="transparent"
        size="sm"
        v-if="config.preview_route"
        v-b-modal.fj-page-preview>
        <fa-icon icon="eye"/> Preview

        <fj-page-preview :route="config.preview_route" v-if="config.preview_route"/>
    </b-button>
</template>

<script>
export default {
    name: 'CrudActionPreview',
    props: {
        config: {
            required: true,
            type: Object
        }
    }
}
</script>
