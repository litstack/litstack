<template>
    <b-button
        size="md"
        variant="primary"
        :href="`${Fjord.baseURL}${config.route_prefix}/create`"
    >
        <fa-icon icon="plus" />
        {{ $t('fj.add_model', { model: config.names.singular }) }}
    </b-button>
</template>

<script>
export default {
    name: 'CrudCreateButton',
    props: {
        config: {
            type: Object,
            required: true
        }
    }
};
</script>
