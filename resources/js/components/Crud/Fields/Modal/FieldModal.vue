<template>
    <fj-form-item :field="field" :model="model">
        <b-button variant="outline-secondary" v-b-modal="modalId">
            {{ field.button }}
        </b-button>
        <b-modal :id="modalId" :size="field.size" :title="field.button">
            <b-row>
                <fj-field
                    v-for="(field, key) in field.form.fields"
                    :key="key"
                    :field="field"
                    :model-id="model.id"
                    :model="model"
                    v-on="$listeners"
                />
            </b-row>
            <template slot="modal-footer">
                <button
                    @click.prevent="
                        $bvModal.hide(`fj-image-${field.id}-${image.id}`)
                    "
                    class="btn btn-secondary"
                >
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
    </fj-form-item>
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
    computed: {
        ...mapGetters(['canSave']),
        modalId() {
            return `fj-field-modal-${this.field.route_prefix.replace(
                /\//g,
                '-'
            )}`;
        }
    }
};
</script>
