<template>
    <b-dropdown-item @click="runAction">
        {{ title }}
    </b-dropdown-item>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudIndexTableAction',
    props: {
        selectedItems: {
            type: Array,
            required: true,
        },
        title: {},
        routeId: {},
        routePrefix: {
            required: true,
            type: String,
        },
    },
    methods: {
        async runAction() {
            try {
                let response = axios.post(
                    `${this.routePrefix}/run-action/${this.routeId}`,
                    { ids: _.map(this.selectedItems, 'id') }
                );
            } catch (e) {
                console.log(e);
                return;
            }

            this.$emit('reload');
        },
    },
    computed: {
        ...mapGetters(['form']),
    },
};
</script>
