<template>
    <fj-form-item :field="field" :model="model">
        <div class="form-control-expand" v-if="model.id">
            <div v-if="busy" class="d-flex justify-content-around">
                <fj-spinner />
            </div>

            <draggable
                v-model="selectedRelations"
                @end="newOrder"
                handle=".fj-draggable__dragbar"
                tag="b-row"
                :class="{ 'mb-0': field.readonly }"
                v-else-if="selectedRelations.length > 0"
            >
                <fj-field-block
                    v-for="(relation, index) in selectedRelations"
                    :key="index"
                    :block="relation"
                    :field="field"
                    :fields="field.form ? field.form.fields : []"
                    :model="model"
                    :sortable="field.many ? field.sortable : false"
                    :preview="field.preview"
                    :set-route-prefix="setFieldsRoutePrefixId"
                    delete-icon="unlink"
                    @deleteItem="removeRelation(relation)"
                />
            </draggable>

            <div v-else>
                <fj-field-alert-empty
                    :field="field"
                    :class="{ 'mb-0': field.readonly }"
                />
            </div>

            <b-button
                variant="secondary"
                size="sm"
                class="mt-2"
                v-b-modal="modalId"
                v-if="!field.readonly"
            >
                {{
                    field.many
                        ? __('fj.add_model', { model: field.title })
                        : __('fj.select_item', { item: field.title })
                }}
            </b-button>

            <fj-field-relation-modal
                :field="field"
                :model="model"
                :modal-id="modalId"
                :selectedRelations="selectedRelations"
                @select="selectRelation"
                @remove="removeRelation"
            />
        </div>
        <template v-else>
            <fj-field-alert-not-created :field="field" class="mb-0" />
        </template>
    </fj-form-item>
</template>

<script>
import methods from '../methods';

export default {
    name: 'FieldRelation',
    props: {
        field: {
            type: Object
        },
        model: {
            type: Object
        },
        modelId: {
            required: true
        }
    },
    data() {
        return {
            selectedRelations: [],
            busy: true
        };
    },
    beforeMount() {
        this.loadRelations();
    },
    methods: {
        ...methods,
        setFieldsRoutePrefixId(fields, relation) {
            for (let i in fields) {
                let field = fields[i];
                fields[i].route_prefix = field.route_prefix.replace(
                    '{id}',
                    relation.id
                );
                if (this.field.readonly) {
                    fields[i].readonly = true;
                }
            }
            return fields;
        },
        async loadRelations() {
            this.busy = true;
            let response = await axios.get(
                `${this.field.route_prefix}/${this.field.id}`
            );
            this.selectedRelations = [];
            for (let i in response.data) {
                let block = response.data[i];
                this.newRelation(block);
            }
            this.busy = false;
        },
        newRelation(relation) {
            this.selectedRelations.push(this.crud(relation));
        },
        async selectRelation(relation) {
            let response = null;
            switch (this.field.type) {
                case 'morphMany':
                case 'hasMany':
                case 'morphedByMany':
                case 'morphToMany':
                case 'belongsToMany':
                case 'manyRelation':
                    try {
                        response = await axios.post(
                            `${this.field.route_prefix}/${this.field.id}/${relation.id}`
                        );
                    } catch (e) {
                        console.log(e);
                        return;
                    }
                    this.$bvToast.toast(
                        this.$t('fj.relation_added', {
                            relation: this.field.config.names.singular
                        }),
                        {
                            variant: 'success'
                        }
                    );
                    break;
                case 'hasOne':
                case 'morphOne':
                case 'oneRelation':
                    try {
                        response = await axios.post(
                            `${this.field.route_prefix}/${this.field.id}/${relation.id}`
                        );
                    } catch (e) {
                        console.log(e);
                        return;
                    }

                    this.$bvToast.toast(
                        this.$t('fj.relation_added', {
                            relation: this.field.title
                        }),
                        {
                            variant: 'success'
                        }
                    );
                    break;
                /*
                case 'morphTo':
                    this.model.attributes[this.field.morph_type] = modelName;
                    this.model.attributes[this.field.foreign_key] = item.id;
                    this.model[`${this.field.id}Model`] = item.attributes;
                    this.setCols();
                    this.$emit('changed', this.field, this.model);
                    break;
                */
                case 'belongsTo':
                    this.setValue(relation.id);
                    this.$emit('changed', relation.id);
                    break;
            }

            if (this.field.many) {
                this.selectedRelations.push(relation);
            } else {
                this.selectedRelations = [relation];
            }

            this.$bvModal.hide(this.modalId);
        },
        async removeRelation(relation) {
            console.log('REM', relation);
            let response = null;
            switch (this.field.type) {
                case 'morphMany':
                case 'hasMany':
                case 'morphedByMany':
                case 'morphToMany':
                case 'belongsToMany':
                case 'manyRelation':
                case 'relation':
                    try {
                        response = await axios.delete(
                            `${this.field.route_prefix}/${this.field.id}/${relation.id}`
                        );
                    } catch (e) {
                        console.log(e);
                        return;
                    }
                    this.$bvToast.toast(this.$t('fj.relation_unlinked'), {
                        variant: 'success'
                    });
                    break;
                case 'hasOne':
                case 'morphOne':
                case 'oneRelation':
                    try {
                        response = axios.delete(
                            `${this.field.route_prefix}/${this.field.id}/${relation.id}`
                        );
                    } catch (e) {
                        console.log(e);
                        return;
                    }
                    this.$bvToast.toast(this.$t('fj.relation_unlinked'), {
                        variant: 'success'
                    });
                    break;
                case 'belongsTo':
                    this.setValue(null);
                    this.$emit('changed', null);
                    break;
            }

            if (this.field.many) {
                for (let i in this.selectedRelations) {
                    if (this.selectedRelations[i].id == relation.id) {
                        this.selectedRelations.splice(i, 1);
                    }
                }
            } else {
                this.selectedRelations = [];
            }
        },
        async newOrder() {
            let payload = {
                ids: this.selectedRelations.map(item => item.id)
            };
            try {
                let response = await axios.put(
                    `${this.field.route_prefix}/${this.field.id}/order`,
                    payload
                );
            } catch (e) {
                console.log(e);
                return;
            }

            this.$bvToast.toast(this.$t('fj.order_changed'), {
                variant: 'success'
            });
        }
    },
    computed: {
        modalId() {
            return `form-relation-table-${this.field.id}-${this.model.id}`;
        }
    }
};
</script>
