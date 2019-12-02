<template>
    <fj-form-item :field="field" v-if="model.id">
        <pre>{{ rows }}</pre>
        <div v-for="(row, index) in rows">
            <input v-model="row.title" />
        </div>
    </fj-form-item>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'FormHasMany',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        }
    },
    methods: {
        async fetchRelation() {
            const { data } = await axios.get(
                `/${this.model.route}/${this.model.attributes.id}/relations/${
                    this.field.id
                }`
            );
            this.rows = data;
        }
    },
    data() {
        return {
            rows: null
        };
    },
    beforeMount() {
        this.fetchRelation();
        this.$bus.$on('modelsSaved', () => {
            console.log('listener from FormHasMany');
        });
    },
    computed: {}
};
</script>
