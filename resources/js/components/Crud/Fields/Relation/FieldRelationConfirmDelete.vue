<template>
    <b-modal
        :id="`modal-${field.id}-${relation.id}`"
        :title="__('base.item_remove', { item: field.names.singular })"
    >
        {{
            field.delete_unlinked
                ? __('base.messages.are_you_sure')
                : __('crud.fields.relation.messages.confirm_unlink')
        }}

        <template v-slot:modal-footer>
            <b-button
                variant="secondary"
                size="sm"
                class="float-right"
                @click="
                    $emit('canceled');
                    $bvModal.hide(`modal-${field.id}-${relation.id}`);
                "
            >
                {{ __('base.cancel').capitalize() }}
            </b-button>
            <a
                href="#"
                @click.prevent="
                    $emit('confirmed', relation);
                    $bvModal.hide(`modal-${field.id}-${relation.id}`);
                "
                class="lit-trash btn btn-danger btn-sm"
            >
                <lit-fa-icon icon="unlink" />
                {{ __('base.delete').capitalize() }}
            </a>
        </template>
    </b-modal>
</template>

<script>
export default {
    name: 'FieldRelationConfirmDelete',
    props: {
        field: {
            required: true,
            type: Object,
        },
        relation: {
            required: true,
            type: Object,
        },
    },
};
</script>

<style></style>
