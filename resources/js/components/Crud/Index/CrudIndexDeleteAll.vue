<template>
    <b-dropdown-item @click="deleteAll">
        Delete {{ selectedItems.length }} Items
    </b-dropdown-item>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudIndexDeleteAll',
    props: {
        selectedItems: {
            type: Array,
            required: true,
        },
        sendAction: {
            required: true,
        },
    },
    methods: {
        async deleteAll() {
            let response = await this.sendAction(
                `${this.form.config.names.table}/delete-all`,
                this.selectedItems
            );

            this.$bus.$emit('reloadCrudIndex');
        },
    },
    computed: {
        ...mapGetters(['form']),
    },
};
</script>
