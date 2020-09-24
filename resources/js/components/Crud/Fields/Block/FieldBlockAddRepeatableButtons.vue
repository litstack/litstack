<template>
    <div class="mt-2">
        <b-button
            class="mr-2"
            :class="`fj-block-add-${type}`"
            size="sm"
            v-for="(repeatables, type) in field.repeatables"
            :key="type"
            @click.prevent="add(type)"
        >
            <fa-icon icon="plus" />
            {{ type }}
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
            required: true
        },
        model: {
            type: Object,
            required: true
        },
        sortableBlocks: {
            type: Array,
            required: true
        }
    },
    computed: {
        ...mapGetters(['form'])
    },
    methods: {
        /**
         * Add repeatable.
         */
        async add(type) {
            let response = await this.sendAddRepeatable({
                repeatable_type: type
            });

            if (!response) {
                return;
            }

            let model = response.data;

            this.$emit('newBlock', model);

            Fjord.bus.$emit('field:updated', 'block:added');

            this.showNewRepeatableToast(type);
        },

        /**
         * Send add repeatable request.
         */
        async sendAddRepeatable(payload) {
            payload = {
                field_id: this.field.id,
                ...(this.field.params || {}),
                payload
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
            this.$bvToast.toast(this.__('fj.new_block', { type }), {
                variant: 'success'
            });
        }
    }
};
</script>
