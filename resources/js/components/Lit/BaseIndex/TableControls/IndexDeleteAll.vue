<template>
    <b-dropdown-item @click="deleteAll">
        Delete {{ selectedItems.length }} Items
    </b-dropdown-item>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'IndexDeleteAll',
    props: {
        selectedItems: {
            type: Array,
            required: true,
        },
        routePrefix: {
            required: true,
            type: String,
        },
    },
    methods: {
        async deleteAll() {
            try {
                let response = await axios.post(
                    `${this.routePrefix}/delete-all`,
                    { ids: _.map(this.selectedItems, 'id') }
                );
            } catch (e) {
                console.log(e);
                return;
            }

            this.$emit('reload');
        },
    },
};
</script>
