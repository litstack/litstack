<template>
    <form class="row" style="margin-bottom: -1.5em;">
        <template v-for="m in preparedModels">
            <template v-for="(field, index) in m.form_fields">
                <div
                    :class="fieldWidth(field)"
                    v-if="ids.length < 1 || ids.includes(field.id)"
                >
                    <fj-form-input
                        v-if="field.type == 'input'"
                        :model="m"
                        :field="field"
                        :readonly="readonly(field)"
                        @changed="changed(field, m)"
                    />

                    <fj-form-boolean
                        v-if="field.type == 'boolean'"
                        :model="m"
                        :field="field"
                        :readonly="readonly(field)"
                        @changed="changed(field, m)"
                    />

                    <fj-form-checkboxes
                        v-if="field.type == 'checkboxes'"
                        :model="m"
                        :field="field"
                        :readonly="readonly(field)"
                        @changed="changed(field, m)"
                    />

                    <fj-form-select
                        v-if="field.type == 'select'"
                        :field="field"
                        :model="m"
                        :readonly="readonly(field)"
                        @changed="changed(field, m)"
                    />

                    <fj-form-textarea
                        v-if="field.type == 'textarea'"
                        :field="field"
                        :model="m"
                        :readonly="readonly(field)"
                        @changed="changed(field, m)"
                    />

                    <fj-form-wysiwyg
                        v-if="field.type == 'wysiwyg'"
                        :field="field"
                        :model="m"
                        :readonly="readonly(field)"
                        @changed="changed(field, m)"
                    />

                    <fj-form-code
                        v-if="field.type == 'code'"
                        :field="field"
                        :model="m"
                        :readonly="readonly(field)"
                        @changed="changed(field, m)"
                    />

                    <fj-form-range
                        v-if="field.type == 'range'"
                        :field="field"
                        :model="m"
                        :readonly="readonly(field)"
                        @changed="changed(field, m)"
                    />

                    <fj-form-date-time
                        v-if="field.type == 'datetime' || field.type == 'dt'"
                        :field="field"
                        :model="m"
                        :readonly="readonly(field)"
                        @changed="changed(field, m)"
                    />

                    <fj-form-media
                        v-if="field.type == 'image'"
                        :field="field"
                        :id="m.id"
                        :model="m"
                        :readonly="readonly(field)"
                        :media="m[field.id]"
                    />

                    <fj-form-block
                        v-if="field.type == 'block'"
                        :field="field"
                        :repeatables="m.relations[field.id]"
                        :model="m"
                        :readonly="readonly(field)"
                        @newRepeatable="
                            repeatable => {
                                newRepeatable(m, repeatable);
                            }
                        "
                    />

                    <fj-form-relation-many
                        v-if="
                            (field.type == 'relation' && field.many) ||
                                field.type == 'hasMany' ||
                                field.type == 'belongsToMany' ||
                                field.type == 'morphMany' ||
                                field.type == 'morphToMany' ||
                                field.type == 'morphedByMany'
                        "
                        :field="field"
                        :model="m"
                        :type="field.type"
                        :readonly="readonly(field)"
                    />
                    <fj-form-relation-one
                        v-if="
                            (field.type == 'relation' && !field.many) ||
                                field.type == 'hasOne' ||
                                field.type == 'belongsTo' ||
                                field.type == 'morphOne' ||
                                field.type == 'morphTo'
                        "
                        :field="field"
                        :model="m"
                        :readonly="readonly(field)"
                        @changed="changed(field, m)"
                    />
                </div>
            </template>
        </template>
    </form>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import FjordModel from '@fj-js/eloquent/fjord.model';
import EloquentCollection from '@fj-js/eloquent/collection';
import { mapGetters } from 'vuex';

export default {
    name: 'FjordForm',
    props: {
        // the model prop is only used in blocks
        model: {
            type: Object
        },
        ids: {
            type: Array,
            default() {
                return [];
            }
        }
    },
    beforeMount() {
        this.init();

        this.$bus.$on('modelLoaded', () => {
            this.init();
        });
    },
    data() {
        return {
            preparedModels: []
        };
    },
    methods: {
        init() {
            let model = this.model || this.crud.model;

            if (model instanceof FjordModel) {
                this.preparedModels = [model];
            } else {
                this.preparedModels = model.items.items;
            }
        },
        changed(field, model) {
            if (model.getOriginalModel(field) == model[`${field.id}Model`]) {
                this.$store.commit('REMOVE_MODELS_FROM_SAVE', {
                    model,
                    id: field.id
                });
            } else {
                this.$store.commit('ADD_MODELS_TO_SAVE', {
                    model,
                    id: field.id
                });
            }
        },
        toggle(field) {
            field.model = !field.model;
        },
        newRepeatable(model, repeatable) {
            model.relations.repeatables.items.items.push(repeatable);
        },
        fieldWidth(field) {
            return field.width !== undefined ? `col-${field.width}` : 'col-12';
        },
        readonly(field) {
            return field.readonly || !this.form.config.permissions.update;
        }
    },
    computed: {
        ...mapGetters(['crud', 'form'])
    }
};
</script>
