<template>
    <b-modal :id="modalId" :size="field.modalSize">
        <span slot="modal-title" v-html="_format(field.previewTitle, item)" />
        <b-row>
            <fj-field
                v-for="(itemField, key) in fields"
                :key="key"
                :field="itemField"
                :model-id="model.id"
                :model="item"
                v-on="$listeners"
            />
        </b-row>
        <template slot="modal-footer">
            <button @click.prevent="cancel()" class="btn btn-secondary">
                {{ __('base.close').capitalize() }}
            </button>
            <b-button
                class="fj-save-button"
                variant="primary"
                v-bind:disabled="!canSave"
                @click="Fjord.bus.$emit('save')"
            >
                {{ __('base.save').capitalize() }}
            </b-button>
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
        modalId: {}
    },
    data() {
        return {
            fields: []
        };
    },
    beforeMount() {
        this.formatRoutePrefixes();
    },
    methods: {
        formatRoutePrefixes() {
            let fields = Fjord.clone(this.field.form.fields);
            for (let i in fields) {
                if (
                    fields[i].component == 'fj-field-media' ||
                    fields[i].component == 'fj-field-relation'
                ) {
                    let partials = fields[i].route_prefix.split('/');
                    partials[partials.length - 3] = 'block';
                    fields[i].route_prefix = partials.join('/');
                }

                if (this.item.id) {
                    fields[i].route_prefix = fields[i].route_prefix.replace(
                        '{list_item_id}',
                        this.item.id ? this.item.id : ''
                    );
                } else {
                    fields[
                        i
                    ].route_prefix = `${this.field.route_prefix}/list/${this.field.id}/${this.item.parent_id}`;
                }
            }

            this.fields = fields;
        }
    },

    computed: {
        ...mapGetters(['canSave'])
    }
};
</script>
