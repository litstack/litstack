<template>
    <lit-base-field :field="field" :model="model">
        <b-button
            :variant="field.variant"
            v-b-modal="modalId"
            v-html="field.name"
            v-if="!field.preview && !field.button_component"
        />

        <div
            v-if="field.preview"
            class="w-100"
            v-html="_format(field.preview, model)"
        />
        <a
            href="#"
            v-b-modal="modalId"
            v-html="field.name"
            @click.prevent=""
            v-if="!field.button_component && field.preview"
        />
        <lit-base-component
            v-else
            v-b-modal="modalId"
            @show="$bvModal.show(modalId)"
            :component="field.button_component"
            :model="model"
        />
        <b-form-invalid-feedback
            v-for="(message, key) in messages"
            :key="key"
            style="display: block;"
        >
            {{ message }}
        </b-form-invalid-feedback>
        <b-modal :id="modalId" :size="field.size" centered>
            <span slot="modal-title" v-html="field.name" />
            <b-row>
                <lit-field
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
                    {{ __('base.save') }}
                </b-button>
            </template>
        </b-modal>
    </lit-base-field>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FieldModal',
    props: {
        field: {
            required: true,
            type: Object,
        },
        model: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            fields: [],
            state: null,
            messages: [],
            modalId: null,
        };
    },
    beforeMount() {
        this.fields = Lit.clone(this.field.form.fields);
        this.setId();

        Lit.bus.$on('saveCanceled', this.resetErrors);
        Lit.bus.$on('saved', this.resetErrors);
    },
    computed: {
        ...mapGetters(['canSave']),
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
        setId() {
            this.modalId = Lit.uuidv4();
        },
    },
};
</script>

<style lang="scss">
.modal-body .lit-field-relation {
    margin-left: -1.5rem;
    margin-right: -1.5rem;
}
</style>
