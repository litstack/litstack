<template>
    <fj-base-field :field="field" :model="model">
        <b-button
            :variant="field.variant"
            v-b-modal="modalId"
            v-html="field.name"
            v-if="!field.preview"
        />
        <template v-else>
            <div class="w-100" v-html="_format(field.preview, model)" />
            <a
                href="#"
                v-b-modal="modalId"
                v-html="field.name"
                @click.prevent=""
            />
        </template>
        <b-form-invalid-feedback
            v-for="(message, key) in messages"
            :key="key"
            style="display:block;"
        >
            {{ message }}
        </b-form-invalid-feedback>
        <b-modal :id="modalId" :size="field.size">
            <span slot="modal-title" v-html="field.name" />
            <b-row>
                <fj-field
                    v-for="(field, key) in fields"
                    :key="key"
                    :field="field"
                    :model-id="model.id"
                    :model="model"
                    @error="error"
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
                    {{ $t('fj.save') }}
                </b-button>
            </template>
        </b-modal>
    </fj-base-field>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FieldModal',
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
            fields: [],
            state: null,
            messages: []
        };
    },
    beforeMount() {
        this.formatRoutePrefixes();
        Fjord.bus.$on('saveCanceled', this.resetErrors);
        Fjord.bus.$on('saved', this.resetErrors);
    },
    computed: {
        ...mapGetters(['canSave']),
        modalId() {
            return `fj-field-modal-${
                this.field.id
            }-${this.field.route_prefix.replace(/\//g, '-')}`;
        }
    },
    methods: {
        resetErrors() {
            this.messages = [];
            this.state = null;
        },
        error(messages) {
            this.state = false;
            this.messages = messages.concat(this.messages);
        },
        cancel() {
            this.$bvModal.hide(this.modalId);
        },
        formatRoutePrefixes() {
            let fields = Fjord.clone(this.field.form.fields);
            for (let i in fields) {
                fields[i].route_prefix = fields[i].route_prefix.replace(
                    '{modal_id}',
                    this.field.id
                );
            }

            this.fields = fields;
        }
    }
};
</script>
