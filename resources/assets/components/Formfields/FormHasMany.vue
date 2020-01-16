<template>
    <fj-form-item :field="field">
        <template v-if="model.id">
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
                                @click="unlinkRelation(m, index)"
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
            <b-button
                variant="secondary"
                size="sm"
                @click="createRelation"
                class="mr-1"
            >
                <fa-icon icon="plus" /> {{ field.form.names.singular }}
            </b-button>
            <b-button
                variant="secondary"
                size="sm"
                @click="addRelation"
                v-b-modal.add-frelation
            >
                <fa-icon icon="link" /> {{ field.form.names.singular }}
            </b-button>
            <b-modal id="add-frelation" size="lg" title="Add Relation">
                <b-table-simple outlined>
                    <thead>
                        <tr>
                            <th
                                colspan="2"
                                v-for="(col, index) in field.form.index.preview"
                                :key="index"
                            >
                                {{ col.label }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(row, key) in unrelatedEloquentModels"
                            :key="key"
                        >
                            <td
                                v-for="(col, index) in field.form.index.preview"
                                :key="index"
                            >
                                <fj-table-col :item="row.data" :col="col" />
                            </td>
                            <td class="text-right">
                                <b-button
                                    variant="secondary"
                                    size="sm"
                                    @click="linkRelation(key, row.data.id)"
                                >
                                    <fa-icon icon="link" />
                                </b-button>
                            </td>
                        </tr>
                    </tbody>
                </b-table-simple>
                <div
                    slot="modal-footer"
                    class="w-100 d-flex justify-content-end"
                >
                    <b-button
                        id="cropper-cancel"
                        variant="secondary"
                        size="sm"
                        @click="$bvModal.hide('add-frelation')"
                    >
                        close
                    </b-button>
                </div>
            </b-modal>
        </template>
        <template v-else>
            <b-alert show variant="warning"
                >{{ form.config.names.title.singular }} has to be created in
                order to add <i>{{ field.title }}</i></b-alert
            >
        </template>
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
            }/relations/${this.field.id}`,
            unrelatedEloquentModels: []
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
        async linkRelation(key, id) {
            try {
                const { data } = await axios.post('link-relation', {
                    id,
                    model: this.field.model,
                    foreign_key: this.field.foreign_key,
                    foreign_id: this.model.id
                });
                if (data.success) {
                    let row = this.unrelatedEloquentModels.splice(key, 1);
                    this.fetchRelation();
                }
            } catch (e) {
            } finally {
            }
        },
        async unlinkRelation(model, index) {
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
        async fetchUnrelated() {
            const { data } = await axios.post('unrelated-relation', {
                model: this.field.model,
                foreign_key: this.field.foreign_key
            });
            this.unrelatedEloquentModels = data;
        },
        fjModel(model) {
            return new FjordModel(model);
        },
        rerenderKey(model, index) {
            return `${model.data.id}-${index}`;
        },
        addRelation() {
            this.fetchUnrelated();
        }
    },
    beforeMount() {
        if (this.model.id !== undefined) {
            this.fetchRelation();
        }
    },
    computed: {
        ...mapGetters(['form']),
        ids() {
            return _.map(this.form_fields, 'id');
        },
        form_fields() {
            return _.flatten(this.field.form.form_fields);
        }
    }
};
</script>
