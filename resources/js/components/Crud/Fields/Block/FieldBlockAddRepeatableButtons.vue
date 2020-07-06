<template>
    <div class="mt-2">
        <b-button
            class="mr-2"
            size="sm"
            v-for="(repeatables, type) in field.repeatables"
            :key="type"
            @click.prevent="add(type)"
        >
            <fa-icon icon="plus" />
            add {{ type }}
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

            this.showNewRepeatableToast();
        },

        /**
         * Send add repeatable request.
         */
        async sendAddRepeatable(payload) {
            try {
                return await axios.post(`${this.field.route_prefix}/block`, {
                    field_id: this.field.id,
                    payload
                });
            } catch (e) {
                console.log(e);
                return;
            }
        },

        /**
         * Show new repeatable toast.
         */
        showNewRepeatableToast() {
            this.$bvToast.toast(this.$t('fj.new_block', { type }), {
                variant: 'success'
            });
        }
    }
};
</script>
