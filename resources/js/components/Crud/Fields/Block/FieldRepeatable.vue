<template>
    <lit-col :width="width">
        <div class="lit-draggable lit-block mb-2 mt-2">
            <lit-field-repeatable-header
                ref="header"
                :sortable="sortable"
                :expand="expand"
                :block="block"
                :field="field"
                :model="model"
                :fields="_fields"
                :preview="preview"
                :delete-icon="deleteIcon"
                @deleteItem="deleteItem"
                @toggleExpand="expand = !expand"
            >
                <b-badge
                    :variant="repeatable.variant"
                    class="repeatable-type-badge"
                >
                    <span
                        v-if="repeatable.icon"
                        v-html="repeatable.icon"
                    ></span>
                    <span v-html="repeatableButtonText.capitalize()"></span>
                </b-badge>
            </lit-field-repeatable-header>
            <div :class="`lit-block-form ${expand ? 'show' : ''}`">
                <lit-field-repeatable-form
                    :block="block"
                    :field="field"
                    :model="model"
                    :fields="_fields"
                    :model-id="modelId"
                    @changed="changed"
                    @reload="_reload"
                />
            </div>
        </div>
        <b-modal
            v-model="showConfirm"
            :title="__('base.item_remove', { item: field.title }).capitalize()"
        >
            {{ __('base.messages.are_you_sure') }}

            <template v-slot:modal-footer>
                <b-button
                    variant="secondary"
                    size="sm"
                    class="float-right"
                    @click="showConfirm = false"
                >
                    {{ __('base.cancel').capitalize() }}
                </b-button>
                <a
                    href="#"
                    @click.prevent="
                        $emit('deleteItem');
                        showConfirm = false;
                    "
                    class="lit-trash btn btn-danger btn-sm"
                >
                    <lit-fa-icon icon="unlink" />
                    {{ __('base.delete').capitalize() }}
                </a>
            </template>
        </b-modal>
    </lit-col>
</template>

<script>
export default {
    name: 'FieldRepeatable',
    props: {
        deleteIcon: {
            type: String,
            default() {
                return 'trash';
            },
        },
        sortable: {
            type: Boolean,
            default() {
                return true;
            },
        },
        block: {
            required: true,
            type: Object,
        },
        field: {
            required: true,
            type: Object,
        },
        model: {
            required: true,
            type: Object,
        },
        modelId: {
            required: true,
        },
        width: {
            type: [String, Number],
            default() {
                return 12;
            },
        },
        setRoutePrefix: {
            required: true,
        },
        preview: {
            type: Array,
            required: true,
        },
        fields: {
            type: Array,
            default() {
                return [];
            },
        },
        reload: {
            type: Function,
            default() {
                return;
            },
        },
    },
    data() {
        return {
            expand: false,
            _fields: [],
            showConfirm: null,
        };
    },
    watch: {
        block(val) {
            setTimeout(() => {
                this.$refs.header.$emit('refresh');
            }, 1);
        },
    },
    beforeMount() {
        this._fields = this.setRoutePrefix(Lit.clone(this.fields), this.block);

        this.$on('expand', expand => {
            this.expand = expand;
        });
        this.$on('refresh', expand => {
            this.$refs.header.$emit('refresh');
        });
    },
    methods: {
        deleteItem() {
            if (!this.field.confirm_delete) {
                return this.$emit('deleteItem');
            }

            this.showConfirm = true;
        },

        /**
         * Refresh table cols on change.
         */
        changed() {
            this.$refs.header.$emit('refresh');
            this.$emit('changed');
        },

        /**
         * Reload repeatables.
         */
        async _reload() {
            if (this.reload) {
                await this.reload(this.block);
            }
            this.$refs.header.$emit('refresh');
        },
    },
    computed: {
        repeatableType() {
            return this.block.attributes.type;
        },
        repeatable() {
            return this.field.repeatables[this.repeatableType];
        },
        repeatableButtonText() {
            return this.repeatable.button || this.repeatableType;
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
.lit-block-form {
    display: none;

    &.show {
        display: block;
    }
    .lit-form-item-title {
        font-weight: $font-weight-normal;
    }
}
@media (min-width: 993px) {
    .lit-draggable.lit-block {
        margin: 0 -32px;
    }
}
</style>
