<template>
    <b-dropdown-item @click="runAction">
        {{ title }}
    </b-dropdown-item>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'TableAction',
    props: {
        selectedItems: {
            type: Array,
            required: true,
        },
        title: {},
        route: {
            required: true,
            type: String,
        },
    },
    methods: {
        async runAction() {
            try {
                let response = axios.post(`${this.route}`, {
                    ids: _.map(this.selectedItems, 'id'),
                });
            } catch (e) {
                console.log(e);
                return;
            }

            this.$emit('reload');
        },
    },
};
</script>
