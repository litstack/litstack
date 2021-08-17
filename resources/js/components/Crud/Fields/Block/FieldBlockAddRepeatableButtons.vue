<template>
    <div class="mt-2 block-repeatable-buttons">
        <b-button
            class="text-capitalize mr-2 mb-2"
            :class="`lit-block-add-${type}`"
            size="sm"
            v-for="(repeatable, type) in field.repeatables"
            :key="type"
            @click.prevent="add(type)"
            :variant="repeatable.variant"
        >
            <lit-fa-icon icon="plus" v-if="!repeatable.icon" />
            <span v-else v-html="repeatable.icon">
                {{ repeatable.icon }}
            </span>
            {{ repeatable.button }}
        </b-button>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FieldBlockAddRepeatableButtons',
    props: {
        field: {
            type: Object,
            required: true,
        },
        model: {
            type: Object,
            required: true,
        },
        sortableBlocks: {
            type: Array,
            required: true,
        },
    },
    computed: {
        ...mapGetters(['form']),
    },
    methods: {
        /**
         * Add repeatable.
         */
        async add(type) {
            let response = await this.sendAddRepeatable({
                repeatable_type: type,
            });

            if (!response) {
                return;
            }

            let model = response.data;

            this.$emit('newBlock', model);

            Lit.bus.$emit('field:updated', 'block:added');

            this.showNewRepeatableToast(type);
        },

        /**
         * Send add repeatable request.
         */
        async sendAddRepeatable(payload) {
            payload = {
                field_id: this.field.id,
                ...(this.field.params || {}),
                payload,
            };

            if ('field_id' in (this.field.params || {})) {
                payload.child_field_id = this.field.id;
            }

            try {
                return await axios.post(
                    `${this.field.route_prefix}/block`,
                    payload
                );
            } catch (e) {
                console.log(e);
                return;
            }
        },

        /**
         * Show new repeatable toast.
         */
        showNewRepeatableToast(type) {
            this.$bvToast.toast(
                this.__('crud.fields.block.messages.new_block', { type }),
                {
                    variant: 'success',
                }
            );
        },
    },
};
</script>

<style scoped>
.block-repeatable-buttons {
    margin-bottom: -16px;
}
</style>
