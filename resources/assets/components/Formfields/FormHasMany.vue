<template>
    <fj-form-item :field="field" v-if="model.id">
        <div class="fjord-block card no-fx">
            <div class="card-body">
                <div v-for="(m, index) in eloquentModels">
                    <fj-form :ids="ids" :model="fjModel(m)" />
                </div>
            </div>
        </div>
    </fj-form-item>
</template>

<script>
import FjordModel from './../../eloquent/fjord.model';
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
            this.eloquentModels = data;
        },
        fjModel(model) {
            return new FjordModel(model);
        }
    },
    data() {
        return {
            rows: null,
            eloquentModels: []
        };
    },
    beforeMount() {
        this.fetchRelation();
    },
    computed: {
        ids() {
            return _.map(this.form_fields, 'id');
        },
        form_fields() {
            return _.flatten(this.field.form.form_fields);
        }
    }
};
</script>
