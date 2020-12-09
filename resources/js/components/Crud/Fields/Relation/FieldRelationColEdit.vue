<template>
    <b-button
        variant="transparent"
        size="sm"
        class="btn-square"
        v-b-tooltip.hover
        :title="__('crud.fields.relation.edit')"
        @click="edit()"
    >
        <lit-fa-icon icon="edit" />
        <lit-field-relation-form
            :item="item"
            :modal-id="modalId"
            :field="field"
            :model="model"
            :form="field.update_form"
            @update="$emit('update')"
        />
    </b-button>
</template>

<script>
export default {
    name: 'FieldRelationColEdit',
    props: {
        item: {
            type: Object,
            required: true,
        },
        field: {
            required: true,
            type: Object,
        },
        model: {
            require: true,
            type: Object,
        },
    },
    beforeMount() {
        this.modalId = this.rowModalId(this.item);
    },
    data() {
        return {
            modalId: '',
        };
    },
    methods: {
        rowModalId(item) {
            return `lit-row-${JSON.stringify(item).hash()}`;
        },
        edit() {
            console.log('EDIT');
            this.$bvModal.show(this.modalId);
        },
    },
};
</script>
