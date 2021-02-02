<template>
    <b-button
        variant="transparent"
        size="sm"
        class="btn-square"
        v-b-tooltip.hover
        :title="
            field.delete_unlinked
                ? __('base.delete').capitalize()
                : __('crud.fields.relation.unlink')
        "
        @click="$emit('unlink', item)"
    >
        <span v-html="icon"></span>
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
    computed: {
        icon() {
            if ('unlink' in this.field.icons) {
                return this.field.icons.unlink;
            }

            return this.field.delete_unlinked
                ? '<i class="fas fa-trash-alt"></i>'
                : '<i class="fas fa-unlink"></i>';
        },
    },
};
</script>
