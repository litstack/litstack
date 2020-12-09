<template>
    <b-button
        variant="transparent"
        size="sm"
        class="btn-square"
        v-b-tooltip.hover
        :title="__('crud.fields.relation.unlink')"
        @click="$emit('unlink', item)"
    >
        <lit-fa-icon :icon="field.delete_unlinked ? 'trash-alt' : 'unlink'" />
    </b-button>
</template>

<script>
export default {
    name: 'FieldRelationColUnlink',
    props: {
        field: {
            type: Object,
            required: true,
        },
        item: {
            type: Object,
            required: true,
        },
    },
};
</script>
