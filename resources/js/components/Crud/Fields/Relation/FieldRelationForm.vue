<template>
    <b-modal :id="modalId" size="lg" :title="field.names.singular">
        <b-row>
            <lit-field
                v-for="(field, key) in fields"
                :key="key"
                :field="field"
                :model-id="relation.id"
                :model="relation"
                @changed="$emit('update')"
            />
        </b-row>
        <template v-slot:modal-footer>
            <div class="d-flex justify-content-between w-100">
                <div>
                    <lit-crud-language />
                </div>
                <b-button
                    class="lit-save-button"
                    variant="primary"
                    :disabled="!canSave"
                    @click="Lit.bus.$emit('save')"
                >
                    {{ __('base.save') }}
                </b-button>
            </div>
        </template>
    </b-modal>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FieldRelationForm',
    props: {
        item: {
            type: Object,
        },
        modalId: {
            required: true,
            type: String,
        },
        field: {
            required: true,
            type: Object,
        },
        model: {
            require: true,
            type: Object,
        },
        form: {
            // required: true,
            type: Object,
        },
    },
    computed: {
        ...mapGetters(['canSave']),
    },
    data() {
        return {
            fields: [],
            relation: {},
        };
    },
    beforeMount() {
        this.relation = this.item;

        if (!this.item) {
            this.resetRelationModel();
        }

        this.fields = this.setFieldsRoutePrefixId(
            Lit.clone(this.form?.fields || {}),
            this.relation
        );

        Lit.bus.$on('saved', () => {
            this.$bvModal.hide(this.modalId);
            if (!this.item) {
                this.resetRelationModel();
            }
        });
    },
    methods: {
        resetRelationModel() {
            this.relation = this.crud({
                attributes: {},
                translatable: false,
                cast: true,
            });
        },
        setFieldsRoutePrefixId(fields, relation) {
            for (let i in fields) {
                let field = fields[i];
                fields[i].route_prefix = field.route_prefix.replace(
                    '{id}',
                    this.model.id
                );
                fields[i].params.relation_id = relation.id;
            }
            return fields;
        },
    },
};
</script>
