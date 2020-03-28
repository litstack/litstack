<template>
    <b-dropdown-item @click="deleteAll">
        Delete {{ selectedItems.length }} Users
    </b-dropdown-item>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'UserDeleteAll',
    props: {
        selectedItems: {
            type: Array,
            required: true
        },
        sendAction: {
            required: true
        }
    },
    methods: {
        async deleteAll() {
            let response = await this.sendAction('users/delete-all', this.selectedItems)

            this.$emit('reload');
        }
    },
};
</script>
