<template>
    <div>
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
import FjordModel from '@fj-js/eloquent/fjord.model';
import { mapGetters } from 'vuex';
export default {
    name: 'FormBlockAddButtons',
    props: {
        field: {
            type: Object,
            required: true
        },
        model: {
            type: Object,
            required: true
        },
        sortableRepeatables: {
            type: Array,
            required: true
        }
    },
    computed: {
        ...mapGetters(['form'])
    },
    methods: {
        async add(type) {
            let payload = {
                type: type,
                field_id: this.field.id,
                value: this.newBlock(type),
                order_column: this.sortableRepeatables.length
            };

            let response = null;
            try {
                response = await axios.post(
                    `${this.form.config.route}/${this.model.id}/blocks`,
                    payload
                );
            } catch (e) {
                console.log(e);
                return;
            }
            let model = new FjordModel(response.data);

            this.$emit('newBlock', model);

            this.$bvToast.toast(this.$t('fj.new_block', { type }), {
                variant: 'success'
            });
        },
        newBlock(type) {
            let obj = {};

            for (var i = 0; i < this.field.repeatables[type].length; i++) {
                if (this.field.repeatables[type][i].id != 'image') {
                    obj[this.field.repeatables[type][i].id] = '';
                }
            }

            return obj;
        }
    }
};
</script>
