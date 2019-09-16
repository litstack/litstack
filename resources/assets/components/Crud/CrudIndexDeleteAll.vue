<template>
    <b-dropdown-item @click="deleteAll">
        Delete {{ selectedItems.length }} Items
    </b-dropdown-item>
</template>

<script>
export default {
    name: 'CrudIndexDeleteAll',
    props: {
        formConfig: {
            required: true,
            type: Object
        },
        selectedItems: {
            type: Array,
            required: true
        },
        sendAction: {
            required: true
        }
    },
    methods: {
        deleteAll() {
            this.sendAction(
                `${this.formConfig.names.table}/delete-all`,
                this.selectedItems
            );
            this.$bus.$emit('reloadCrudIndex');
        }
    }
};
</script>

<style lang="css" scoped></style>
