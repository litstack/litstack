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
            required: true,
        },
    },
    methods: {
        async deleteAll() {
            try {
                let response = await axios.post('fjord/users/delete-all', {
                    ids: this.selectedItems,
                });
            } catch (e) {
                return;
            }

            this.$emit('reload');
        },
    },
};
</script>
