<template>
    <b-modal :id="`modal-${routePrefix}-${relation.id}`" title="Unlink Item">
        {{ $t('fj.confirm_unlink') }}

        <template v-slot:modal-footer>
            <b-button
                variant="secondary"
                size="sm"
                class="float-right"
                @click="$bvModal.hide(`modal-${routePrefix}-${relation.id}`)"
            >
                {{ $t('fj.cancel').capitalize() }}
            </b-button>
            <a
                href="#"
                @click.prevent="
                    $emit('confirmed', {
                        id: relation.id,
                        modelName: model
                    });
                    $bvModal.hide(`modal-${routePrefix}-${relation.id}`);
                "
                class="fj-trash btn btn-danger btn-sm"
            >
                <fa-icon icon="unlink" />
                {{ $t('fj.delete').capitalize() }}
            </a>
        </template>
    </b-modal>
</template>

<script>
export default {
    name: 'FormRelationConfirmDelete',
    props: {
        model: {
            required: true,
            type: String
        },
        relation: {
            required: true,
            type: String
        },
        routePrefix: {
            required: true,
            type: String
        }
    }
};
</script>

<style></style>
