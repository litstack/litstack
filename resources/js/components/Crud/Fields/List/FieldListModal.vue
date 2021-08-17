<template>
    <b-modal :id="modalId" :size="field.modalSize" centered>
        <template v-slot:modal-title>
            {{
                __('base.item_add', {
                    item: __('base.item_item', { item: field.title }),
                })
            }}
        </template>
        <b-row>
            <lit-field
                v-for="(itemField, key) in fields"
                :key="key"
                :field="itemField"
                :model-id="model.id"
                :model="item"
                v-on="$listeners"
            />
        </b-row>
        <template v-slot:modal-footer>
            <div class="d-flex justify-content-between w-100">
                <div>
                    <lit-crud-language />
                </div>
                <div>
                    <button
                        @click.prevent="$bvModal.hide(modalId)"
                        class="btn btn-secondary"
                    >
                        {{ __('base.close').capitalize() }}
                    </button>
                    <b-button
                        class="lit-save-button"
                        variant="primary"
                        v-bind:disabled="!canSave"
                        @click="Lit.bus.$emit('save')"
                    >
                        {{ __('base.save').capitalize() }}
                    </b-button>
                </div>
            </div>
        </template>
    </b-modal>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FieldListModal',
    props: {
        item: {},
        field: {},
        model: {},
        modalId: {},
    },
    data() {
        return {
            fields: [],
        };
    },
    beforeMount() {
        this.formatRoutePrefixes();
    },
    methods: {
        formatRoutePrefixes() {
            let fields = Lit.clone(this.field.form.fields);
            for (let i in fields) {
                fields[i].params.list_item_id = this.item.id;

                if (!this.item.id) {
                    fields[i].route_prefix += '/store';
                    fields[i].params.parent_id = this.item.parent_id;
                }
            }

            this.fields = fields;
        },
    },

    computed: {
        ...mapGetters(['canSave']),
    },
};
</script>
