<template>
    <fj-form-item :field="field" v-if="model.id">
        <div
            class="fjord-block card no-fx mb-2"
            v-for="(m, index) in eloquentModels"
        >
            <div class="card-body">
                <fj-form
                    :ids="ids"
                    :model="fjModel(m)"
                    :key="rerenderKey(m, index)"
                />
                <div class="d-flex justify-content-end">
                    <b-button-group>
                        <b-button
                            variant="outline-secondary"
                            size="sm"
                            @click="removeRelation(m, index)"
                        >
                            <fa-icon icon="unlink" />
                        </b-button>
                        <b-button
                            variant="outline-secondary"
                            size="sm"
                            @click="deleteRelation(m, index)"
                        >
                            <fa-icon icon="trash" />
                        </b-button>
                    </b-button-group>
                </div>
            </div>
        </div>
        <b-button variant="secondary" size="sm" @click="createRelation">
            create
        </b-button>
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
    data() {
        return {
            rows: null,
            eloquentModels: [],
            baseRoute: `/${this.model.route}/${
                this.model.attributes.id
            }/relations/${this.field.id}`
        };
    },
    methods: {
        async fetchRelation() {
            const { data } = await axios.get(`${this.baseRoute}`);
            this.eloquentModels = data;
        },
        async createRelation() {
            const { data } = await axios.get(`${this.baseRoute}/create`);
            this.eloquentModels.push(data);
        },
        async removeRelation(model, index) {
            try {
                const { data } = await axios.post(
                    `${this.baseRoute}/${model.data.id}/remove`
                );
                if (data.success) {
                    this.eloquentModels.splice(index, 1);
                }
            } catch (e) {
            } finally {
            }
        },
        async deleteRelation(model, index) {
            try {
                const { data } = await axios.delete(
                    `${this.baseRoute}/${model.data.id}`
                );
                if (data.success) {
                    this.eloquentModels.splice(index, 1);
                }
            } catch (e) {
            } finally {
            }
        },
        fjModel(model) {
            return new FjordModel(model);
        },
        rerenderKey(model, index) {
            return `${model.data.id}-${index}`;
        }
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
