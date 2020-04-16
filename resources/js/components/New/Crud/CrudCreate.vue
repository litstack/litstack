<template>
    <fj-base-container>
        <fj-base-header :title="config.names.plural"></fj-base-header>

        <b-row>
            <b-col cols="9">
                <b-card v-for="(ids, key) in config.form.cards" :key="key">
                    <fj-crud-field
                        v-for="(id, k) in ids"
                        :key="k"
                        :field="getFieldById(id)"
                        :model="model"
                        :readonly="readonly(getFieldById(id))"
                    />
                </b-card>
            </b-col>
            <b-col cols="3">
                <fj-crud-controls :config="config" :create="true" />
            </b-col>
        </b-row>
    </fj-base-container>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudCreate',
    props: {
        config: {
            required: true,
            type: Object
        },
        models: {
            requried: true
        }
    },
    data() {
        return {
            model: {}
        };
    },
    beforeMount() {
        this.$store.commit('SET_MODEL', this.models.model);
        this.model = this.models.model;
    },
    methods: {
        getFieldById(id) {
            for (let i in this.config.form.fields) {
                let field = this.config.form.fields[i];
                if (field.id == id) {
                    return field;
                }
            }
        },
        readonly(field) {
            return field.readonly === true || !this.config.permissions.update;
        }
    }
};
</script>
